<?php 
namespace Beneus\hreflang\Block;


class HrefLang extends \Magento\Framework\View\Element\Template
{

	protected $_storeManager;
	protected $_register;
	protected $_urlRewriteFactory;
	protected $_scopeConfig;
	protected $_store;
	protected $_product;
	protected $_categoryRepository;
	protected $_productRepository;
	protected $_productFactory;
	protected $_categoryFactory;
	protected $_categoryHelper;

	public function __construct(
		\Magento\Store\Model\StoreManager $storeManager,
		\Magento\Store\Api\Data\StoreInterface $store1,
		\Magento\Catalog\Model\Product $product,
		\Magento\Framework\Registry $register,
		\Magento\Catalog\Helper\Category $categoryHelper,
		\Magento\UrlRewrite\Model\ResourceModel\UrlRewriteFactory $urlRewriteFactory,
		\Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
		\Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
		\Magento\Catalog\Model\ProductFactory $productFactory,
		\Magento\Catalog\Model\CategoryFactory $categoryFactory,
		\Magento\Framework\View\Element\Template\Context $context
	)
	{
		$this->_storeManager = $storeManager;
		$this->_store = $store1;
		$this->_register = $register;
		$this->_urlRewriteFactory = $urlRewriteFactory;
		$this->_product = $product;
		$this->_categoryRepository = $categoryRepository;
		$this->_productRepository = $productRepository;
		$this->_productFactory = $productFactory;
		$this->_categoryFactory = $categoryFactory;
		$this->_categoryHelper = $categoryHelper;
		parent::__construct($context);
	}

	public function getHrefLang()
	{
		$ret = PHP_EOL;
		$url = '';
		$category = $this->_register->registry('current_category');
		$product = $this->_register->registry('current_product');
		$stores = $this->_storeManager->getStores();
		
		foreach ($stores as $store) {
				$storeId = $store->getId();

                if ($product) {
                	$productId = $product->getId();
                	$url =  $this->_productFactory->create()->setStoreId($storeId)->load($productId)->getProductUrl();
                  
                } else if($category){
                	 //$category ? $categoryId = $category->getId() : $categoryId = null;
                	 //$url = $category->setStoreId($store->getId())->getUrlModel()->getUrl();
                	$categoryId = $category->getId();
                	$cat =  $this->_categoryRepository->get($categoryId , $storeId);
                	$cat =  $this->_categoryFactory->create()->setStoreId($storeId)->load($categoryId);



//$url = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB) . $store->getCode() .'/' . $cat->getUrlKey();
//$url = $this->_categoryFactory->create()->setData(
//$this->_categoryRepository->get($categoryId, $storeId)->getData())->getUrl();
                	$url = $cat->getCategoryIdUrl();
                	//$url =  $this->_categoryFactory->create()->setStoreId($storeId)->load($categoryId)->getUrl();

                }else {
                    $url = $store->getCurrentUrl();
                }


               //$storeCode = substr($this->_scopeConfig->getValue('general/locale/code', $store->getId()), 0, 2);

//$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
//$store = $objectManager->get('Magento\Framework\Locale\Resolver'); 


$locale_code = $this->_scopeConfig->getValue('general/locale/code', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);

$ret .= '<link ref="alternate" hreflang="'. $locale_code .'" href="' . $url . '" />' .PHP_EOL;
            }

           $ret.= PHP_EOL; 
         return $ret;

	}

	
}
