<?php

require_once APP_ROOT . '/helpers/azure-storage-blob/vendor/autoload.php';

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Blob\BlobSharedAccessSignatureHelper;
use MicrosoftAzure\Storage\Blob\Models\CreateBlockBlobOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;
use MicrosoftAzure\Storage\Blob\Models\DeleteBlobOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateBlobOptions;
use MicrosoftAzure\Storage\Blob\Models\GetBlobOptions;
use MicrosoftAzure\Storage\Blob\Models\ContainerACL;
use MicrosoftAzure\Storage\Blob\Models\SetBlobPropertiesOptions;
use MicrosoftAzure\Storage\Blob\Models\ListPageBlobRangesOptions;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Common\Exceptions\InvalidArgumentTypeException;
use MicrosoftAzure\Storage\Common\Internal\Resources;
use MicrosoftAzure\Storage\Common\Internal\StorageServiceSettings;
use MicrosoftAzure\Storage\Common\Models\Range;
use MicrosoftAzure\Storage\Common\Models\Logging;
use MicrosoftAzure\Storage\Common\Models\Metrics;
use MicrosoftAzure\Storage\Common\Models\RetentionPolicy;
use MicrosoftAzure\Storage\Common\Models\ServiceProperties;

class Azurestorage extends Controller {
    //
    protected $get;
    protected $post;
    protected $connectionString;
    protected $blobClient;
    
    public function index() {
    
        //
        $this->post = (object) filter_input_array(INPUT_POST);
        //echo json_encode(['status' => false, 'message' => $this->post]); exit;
    
        // action
        $method = $this->post->action;
        if (!method_exists($this, $method)) {
            echo json_encode(['status' => false, 'message' => 'ACTION REQUIRED']); exit;
        }
    
        //$connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('ACCOUNT_NAME').";AccountKey=".getenv('ACCOUNT_KEY');
        $this->connectionString = AZURE_STORAGE;

        // Create blob client.
        $this->blobClient = BlobRestProxy::createBlobService($this->connectionString);
        
        //
        echo json_encode($this->{$method}());
    
    }
    
    public function index2($arg = []) {
    
        //
        $this->post = (object) filter_var_array($arg);
    
        // action
        $method = $this->post->action;
        if (!method_exists($this, $method)) {
            return ['status' => false, 'message' => 'ACTION REQUIRED']; exit;
        }
    
        //$connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('ACCOUNT_NAME').";AccountKey=".getenv('ACCOUNT_KEY');
        $this->connectionString = AZURE_STORAGE;

        // Create blob client.
        $this->blobClient = BlobRestProxy::createBlobService($this->connectionString);
        
        //
        return $this->{$method}();
    
    }
    
    public function listFiles(): array {
    
        $containerName = $this->post->containerName;
        $directory = $this->post->directory;
        $files = [];
        
        try {
    
            // List blobs.
            $listBlobsOptions = new ListBlobsOptions();
            $listBlobsOptions->setPrefix($directory);
    
            do {
                $result = $this->blobClient->listBlobs($containerName, $listBlobsOptions);
                foreach ($result->getBlobs() as $blob) {
                    //echo $blob->getName().": ".$blob->getUrl()."<br />";
                    $file = [
                        'name' => str_ireplace($directory, '', $blob->getName() ?? ''),
                        'url' => $blob->getUrl(),
                    ];
                    
                    $properties = $blob->getProperties(); //($containerName, $blob->getName());
                    $file = array_merge($file, [
                        'getCacheControl' => $properties->getCacheControl(),
                        'getContentEncoding' => $properties->getContentEncoding(),
                        'getContentLanguage' => $properties->getContentLanguage(),
                        'getContentType' => $properties->getContentType(),
                        'getContentLength' => $properties->getContentLength(),
                        'getContentMD5' => $properties->getContentMD5(),
                        'getLastModified' => $properties->getLastModified()->format('Y-m-d H:i:s'),
                        'getBlobType' => $properties->getBlobType(),
                        'getLeaseStatus' => $properties->getLeaseStatus(),
                        'getSequenceNumber' => $properties->getSequenceNumber(),
                    ]);
                    
                    array_push($files, $file);
                }
        
                $listBlobsOptions->setContinuationToken($result->getContinuationToken());
            } while($result->getContinuationToken());
            
        }
        catch(ServiceException $e) {
            return ['status' => false, 'message' => $e->getCode() . ': ' . $e->getMessage()];
        }
        catch(InvalidArgumentTypeException $e) {
            return ['status' => false, 'message' => $e->getCode() . ': ' . $e->getMessage()];
        }
    
        return ['status' => true, 'data' => $files];
    }
    
    public function createFile(): array {
        //
        $containerName = $this->post->containerName;
        $directory = $this->post->directory;
        $file = [];
    
        try {
    
            // upload file
            $upfile = $_FILES['file'];
            if (!isset($upfile['error']) || is_array($upfile['error'])) {
                throw new RuntimeException('INVALID PARAMETERS');
            }
    
            // Check $_FILES['upfile']['error'] value.
            switch ($upfile['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new RuntimeException('NO FILE SENT');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException('EXCEEDED FILESIZE LIMIT');
                default:
                    throw new RuntimeException('UNKNOWN ERRORS');
            }
    
            // You should also check filesize here.
            if ($upfile['size'] > (4 * 1024 * 1024)) {
                throw new RuntimeException('EXCEEDED FILESIZE LIMIT');
            }
    
            // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
            // Check MIME Type by yourself.
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            //return ['status' => false, 'message' => $this->mime_types];
            if (false === $ext = array_search($finfo->file($upfile['tmp_name']), $this->mime_types, true)) {
                throw new RuntimeException('INVALID FILE FORMAT');
            }
    
            // You should name it uniquely.
            // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
            // On this example, obtain safe unique name from its binary data.
            //if (!move_uploaded_file(
            //    $upfile['tmp_name'],
            //    sprintf('./uploads/%s.%s',
            //        sha1_file($_FILES['upfile']['tmp_name']),
            //        $ext
            //    )
            //)) {
            //    throw new RuntimeException('Failed to move uploaded file.');
            //}
    
            $content = fopen($upfile['tmp_name'], "r");
            $newName = sprintf('%s.%s', $this->post->newName, $ext);
    
            // set properties
            //$options = new SetBlobPropertiesOptions();
            //$options->setBlobCacheControl('test');
            //$options->setBlobContentEncoding('UTF-8');
            //$options->setBlobContentLanguage('en-us');
            //$options->setBlobContentLength(512);
            //$options->setBlobContentMD5(null);
            //$options->setBlobContentType('text/plain');
            //$options->setSequenceNumberAction('increment');
    
            $options = new CreateBlockBlobOptions();
            $options->setContentType($this->mime_types[$ext]);
            $options->setContentEncoding('UTF-8');
    
            //Upload blob
            if (!$this->blobClient->createBlockBlob($containerName, $directory . $newName, $content, $options)) {
                throw new ServiceException('FAILED TO UPLOAD FILE');
            }
            
            // load blob
            $listBlobsOptions = new ListBlobsOptions();
            $listBlobsOptions->setPrefix($directory . $newName);
    
            do {
                $result = $this->blobClient->listBlobs($containerName, $listBlobsOptions);
                foreach ($result->getBlobs() as $blob) {
                    //echo $blob->getName().": ".$blob->getUrl()."<br />";
                    $file = [
                        'name' => str_ireplace($directory, '', $blob->getName() ?? ''),
                        'url' => $blob->getUrl()
                    ];
            
                    $properties = $blob->getProperties(); //($containerName, $blob->getName());
                    $file = array_merge($file, [
                        'getCacheControl' => $properties->getCacheControl(),
                        'getContentEncoding' => $properties->getContentEncoding(),
                        'getContentLanguage' => $properties->getContentLanguage(),
                        'getContentType' => $properties->getContentType(),
                        'getContentLength' => $properties->getContentLength(),
                        'getContentMD5' => $properties->getContentMD5(),
                        'getLastModified' => $properties->getLastModified()->format('Y-m-d H:i:s'),
                        'getBlobType' => $properties->getBlobType(),
                        'getLeaseStatus' => $properties->getLeaseStatus(),
                        'getSequenceNumber' => $properties->getSequenceNumber(),
                    ]);
            
                    break;
                }
        
                $listBlobsOptions->setContinuationToken($result->getContinuationToken());
            } while($result->getContinuationToken());
    
        } catch (RuntimeException $e) {
    
            return ['status' => false, 'message' => $e->getMessage()];
    
        }
        
        return ['status' => true, 'data' => $file];
    }
    
    public function deleteFile(): array {
        //
        $containerName = $this->post->containerName;
        $directory = $this->post->directory;
        $file = $this->post->file;
    
        //delete blob
        $this->blobClient->deleteBlob($containerName, $directory . $file);
    
        return ['status' => true, 'data' => 'FILE DELETED'];
    
    }
    
}