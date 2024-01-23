<?php

declare(strict_types=1);

namespace Jenyamba\ParseXml\Service;

use Jenyamba\ParseXml\Logger\Logger;
use Jenyamba\ParseXml\Model\LoggerFactory;
use Jenyamba\ParseXml\Model\LogsRepository;
use Jenyamba\ParseXml\Model\StoreMappingRepository;
use Jenyamba\ParseXml\Api\StoreMappingRepositoryInterface;
use Exception;
use Magento\Quote\Model\ResourceModel\Quote as QuoteResource;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\QuoteFactory;
use Magento\Quote\Model\QuoteManagement;
use Magento\Sales\Api\OrderManagementInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

class OrderService
{
    const SHIPPING_METHOD = 'flatrate_flatrate';

    const PAYMENT_METHOD = 'cashondelivery';

    const REGION_ID = '486';

    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var ParserService
     */
    private ParserService $parserService;

    /**
     * @var LoggerFactory
     */
    private LoggerFactory $loggerFactory;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var OrderManagementInterface
     */
    private OrderManagementInterface $orderManagement;

    /**
     * @var QuoteManagement
     */
    private QuoteManagement $quoteManagement;

    /**
     * @var CustomerRepositoryInterface
     */
    private CustomerRepositoryInterface $customerRepository;

    /**
     * @var CartRepositoryInterface
     */
    private CartRepositoryInterface $quoteRepository;

    /**
     * @var QuoteFactory
     */
    private QuoteFactory $quoteFactory;

    /**
     * @var LogsRepository
     */
    private LogsRepository $logsRepository;

    /**
     * @var Logger
     */
    private Logger $logger;

    /**
     * @var StoreMappingRepositoryInterface
     */
    private StoreMappingRepositoryInterface $storeMapping;

    /**
     * @var CustomerInterface
     */
    protected $customerFactory;

    /**
     * @var QuoteResource
     */
    protected $quoteResource;


    /**
     * @param ParserService $parserService
     * @param StoreManagerInterface $storeManager
     * @param StoreMappingRepository $storeMapping
     * @param Logger $logger
     * @param LogsRepository $logsRepository
     * @param OrderManagementInterface $orderManagement
     * @param CartRepositoryInterface $quoteRepository
     * @param CustomerInterfaceFactory $customerFactory
     * @param QuoteResource $quoteResource
     * @param LoggerFactory $loggerFactory
     * @param QuoteFactory $quoteFactory
     * @param QuoteManagement $quoteManagement
     * @param ProductRepositoryInterface $productRepository
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        ParserService                   $parserService,
        StoreManagerInterface           $storeManager,
        StoreMappingRepositoryInterface $storeMapping,
        Logger                          $logger,
        LogsRepository                  $logsRepository,
        OrderManagementInterface        $orderManagement,
        CartRepositoryInterface         $quoteRepository,
        CustomerInterfaceFactory        $customerFactory,
        QuoteResource                   $quoteResource,
        LoggerFactory                   $loggerFactory,
        QuoteFactory                    $quoteFactory,
        QuoteManagement                 $quoteManagement,
        ProductRepositoryInterface      $productRepository,
        CustomerRepositoryInterface     $customerRepository
    ) {
        $this->parserService = $parserService;
        $this->loggerFactory = $loggerFactory;
        $this->customerFactory = $customerFactory;
        $this->quoteResource = $quoteResource;
        $this->customerRepository = $customerRepository;
        $this->logsRepository = $logsRepository;
        $this->storeMapping = $storeMapping;
        $this->logger = $logger;
        $this->quoteFactory = $quoteFactory;
        $this->storeManager = $storeManager;
        $this->productRepository = $productRepository;
        $this->quoteRepository = $quoteRepository;
        $this->orderManagement = $orderManagement;
        $this->quoteManagement = $quoteManagement;
    }

    public function createOrderFromXmlData(): void
    {
        $result = $this->parserService->getArrayWithDataFromXMLFile();
        $order = $this->prepareOrderFromXmlData($result);
        try {
            $this->placeOrderFromXmlData($order);
        } catch (NoSuchEntityException $e) {
            $this->logger->error("Order was not created because no such entity");
        } catch (LocalizedException $e) {
            $this->logger->error("Order was not created because something went wrong");
        }
    }

    /**
     * Preparing data from xml for order
     * @param array $result
     * @return array
     */
    public function prepareOrderFromXmlData(array $result): array
    {
        $orderValue = $result['orders']['order']['_value'];
        $billingAddress = $orderValue['customer']['billing-address'];
        $orderItems = $orderValue['product-lineitems']['product-lineitem'];
        return [
            'currency_id' => $orderValue['currency'],
            'email' => $orderValue['customer']['customer-email'],
            'storeId' => $orderValue['customer-locale'],
            'shipping_address' => [
                'firstname' => $billingAddress['first-name'],
                'lastname' => $billingAddress['last-name'],
                'street' => $billingAddress['address2'],
                'city' => $billingAddress['city'],
                'country_id' => $billingAddress['country-code'],
                'postcode' => $billingAddress['postal-code'],
                'telephone' => $billingAddress['phone'],
                'shipping_items' => ($orderValue['shipping-lineitems']['shipping-lineitem'])
            ],
            'items' => [
                [
                    'product_id' => $orderItems['product-id'],
                    'qty' => $orderItems['quantity']['_value'],
                    'price' => $orderItems['base-price'],
                    'order_id' => $orderValue['original-order-no']
                ]
            ]
        ];
    }

    /**
     * @param $orderData
     * @return array
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function placeOrderFromXmlData($orderData): array
    {
        $store = $orderData['storeId'];
        $websiteId = $this->storeMapping->getStoreCodeByXmlValue($this->storeManager->getStore()->getWebsiteId());

        $customer = $this->customerFactory->create();
        $customer->setWebsiteId($websiteId);

        $customer = $this->customerRepository->get($orderData['email'], $websiteId);
        $storeCode = $this->storeMapping->getStoreCodeByXmlValue($store);
        if (!$customer->getId()) {
            $storeCode = $this->storeMapping->getStoreCodeByXmlValue($store);
            $customer->setWebsiteId($websiteId)
                ->setStoreId($storeCode)
                ->setFirstname($orderData['shipping_address']['firstname'])
                ->setLastname($orderData['shipping_address']['lastname'])
                ->setEmail($orderData['email']);
            try {
                $this->customerRepository->save($customer);
            } catch (Exception $e) {
                $this->logger->error("Customer was not saved because something went wrong");
            }
        }
        $quote = $this->quoteFactory->create();
        $this->logger->info("Quote was created");
        /** @var Store $quote */
        $quote->setStore($this->storeManager->getStore($storeCode));

        $customer = $this->customerRepository->getById($customer->getId());
        $quote->setCurrency();
        $quote->assignCustomer($customer);

        foreach ($orderData['items'] as $item) {
            try {
                $productSKU = $item['product_id'];
                $itemPrice = $item['price'];
                $itemOrderId = $item['order_id'];
                $itemQty = $item['qty'];
                $product = $this->productRepository->get($productSKU);
                $product->setPrice($itemPrice);
                $quote->addProduct($product,intval($itemQty));
                $this->logger->info("Product with SKU '$productSKU' was added to the cart");
            } catch (NoSuchEntityException $e) {
                $this->logger->error("Product was not added to the cart because doesn`t exist product with the SKU '$productSKU'");
                $this->orderManagement->cancel($item['order_id']);
                $this->logger->error("Order with id '$itemOrderId' was  canceled because something went wring");
            } catch (LocalizedException $e) {
                $this->logger->error("Product was not added to the cart because doesn`t exist product with the SKU '$productSKU' ");
            }
        }
        $quote->getBillingAddress()->addData($orderData['shipping_address'])->setRegion(self::REGION_ID);
        $quote->getShippingAddress()->addData($orderData['shipping_address'])->setRegion(self::REGION_ID);

        $shippingAddress = $quote->getShippingAddress();
        $shippingAddress->setCollectShippingRates(true)
            ->collectShippingRates()
            ->setShippingMethod(self::SHIPPING_METHOD);
        try {
            $quote->setPaymentMethod(self::PAYMENT_METHOD);
            $quote->setInventoryProcessed(false);
            $this->quoteResource->save($quote);
            $this->logger->info("Quote was created and product with SKU '$productSKU' was added to the cart");
            $quote->getPayment()->importData(['method' => 'cashondelivery']);
            $quote->collectTotals();
            $this->quoteRepository->save($quote);
        } catch (NoSuchEntityException $e) {
            $this->logger->error("Product with SKU '$productSKU' was not added to the quote");
        } catch (Exception $e) {
            $this->logger->error("Quote was  not saved because something went wrong");
        }
        $order = $this->quoteManagement->submit($quote);
        $order->setEmailSent(0);
        if ($order->getEntityId()) {
            $result['order_id'] = $order->getRealOrderId();
        } else {
            $result = ['error' => 1, 'msg' => 'Something went wrong :( '];
        }
        return $result;
    }

    /**
     * @return void
     */
    public function insertDataToDb()
    {
        $loggerData = $this->logger->getHandlers()[0];
        $fileName = basename((string)$loggerData->getUrl());
        foreach ($this->logger->getMessages() as $key => $message) {
            $model = $this->loggerFactory->create();
            $model->setData('status', $this->logger->getStatuses()[$key]);
            $model->setData('message', $message);
            $model->setData('file', $fileName);
            try {
                $this->logsRepository->save($model);
            } catch (AlreadyExistsException|Exception $exception) {
                $this->logger->error("Data was not inserted  to table because already exist");
            }
        }
    }
}
