<?php
session_start();

require_once '../imports/firebase.php';

// If id is not present return to the list
if( ! isset($_GET['id'])){
    header('Location:/backpanel/company-list.php', true);
    exit;
}

$post = $_POST;
$files = $_FILES;

$id = isset($post['id']) ? $post['id'] : $_GET['id'];

$companyRef = $database->getReference('/' . $id);
$company = $companyRef->getSnapshot()->getValue();

// Handle POST
if(isset($post) && count($post) > 0){
    if(isset($files['Logo_Image']) && $files['Logo_Image']['name']){
        $storage = $factory->createStorage();
        $bucket = $storage->getBucket();
        $name = $files['Logo_Image']['name'];
        $file = file_get_contents($files['Logo_Image']['tmp_name']);

        $bucket->upload($file, [
            'name' => $name,
            'predefinedAcl' => 'publicRead',
        ]);

        $post['Logo_Image'] = 'https://storage.googleapis.com/asalgayan-e932b.appspot.com/' . $name;
    }else{
        $post['Logo_Image'] = $company['Logo Image'];
    }

    $data = [];
    foreach($post as $key => $value){
        if($key == 'id'){
            continue;
        }
        $data[str_replace('_', ' ', $key)] = $value;
    }

    // Update
    $companyRef->set($data);

    $_SESSION['message'] = 'Company successfully updated';

    header('Location:/backpanel/company-list.php', true);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>bizRabbit</title>

    <link rel="stylesheet" href="../dist/css/styles.css">
</head>
<body class="bg-gray-200">

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-20 mb-10">
    <div class="flex justify-between mb-5">
      <h3 class="text-lg">Edit company</h3>
      <a href="/backpanel/company-list.php" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
        Back to list
      </a>
    </div>
    
    <div class="flex justify-center main">
      <div class="w-10/12 bg-white p-8 rounded-lg shadow-md ">
        <form action="" method="POST" enctype="multipart/form-data" class="space-y-8">
            <input type="hidden" name="id" value="<?php echo $_GET['id'];?>">

          <div class="space-y-8 divide-y divide-gray-200 sm:space-y-5">
            <div class="sm:mt-5 space-y-6 sm:space-y-5">
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200">
                <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                  Company Name
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                  <div class="max-w-lg flex rounded-md shadow-sm">
                    <input type="text" name="Company Name" autocomplete="off" value="<?php echo $company['Company Name']?>" class="flex-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 min-w-0 rounded sm:text-sm border-gray-300">
                  </div>
                </div>
              </div>
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200">
                <label for="photo" class="block text-sm font-medium text-gray-700">
                  Logo Image
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                  <div class="flex items-center">
                    <span id="logo-preview" class="bg-center bg-cover bg-gray-100 h-12 h-20 overflow-hidden rounded shadow-md w-12 w-20" style="background-image:url('<?php echo isset($company['Logo Image']) ? $company['Logo Image'] : '';?>')"></span>
                    <input type="file" name="Logo Image" style="display:none" id="logo-input">
                    <button type="button" id="logo-trigger" class="ml-5 bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                      Change
                    </button>
                  </div>
                </div>
              </div>
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start  sm:pt-5">
                <label for="about" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                  About
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                  <textarea name="About" rows="3" class="max-w-lg shadow-sm block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border border-gray-300 rounded-md"><?php echo isset($company['About']) ? $company['About'] : '';?></textarea>
                </div>
              </div>
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200">
                <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                  Focused on
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                  <div class="max-w-lg flex rounded-md shadow-sm">
                    <input type="text" name="Focused on" autocomplete="off" value="<?php echo $company['Focused on']?>" class="flex-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 min-w-0 rounded sm:text-sm border-gray-300">
                  </div>
                </div>
              </div>
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200">
                <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                  Business type
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                  <div class="max-w-lg flex rounded-md shadow-sm">
                    <input type="text" name="Business type" autocomplete="off" value="<?php echo $company['Business type']?>" class="flex-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 min-w-0 rounded sm:text-sm border-gray-300">
                  </div>
                </div>
              </div>
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200">
                <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                  Website
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                  <div class="max-w-lg flex rounded-md shadow-sm">
                    <input type="text" name="Website" autocomplete="off" value="<?php echo $company['Website']?>" class="flex-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 min-w-0 rounded sm:text-sm border-gray-300">
                  </div>
                </div>
              </div>
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200">
                <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                  Sub tags
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                  <div class="max-w-lg flex rounded-md shadow-sm">
                      <textarea name="sub tags" rows="3" class="max-w-lg shadow-sm block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border border-gray-300 rounded-md"><?php echo $company['sub tags']?></textarea>
                  </div>
                </div>
              </div>
                <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                        Tech Specs
                    </label>
                    <div class="mt-1 sm:mt-0 sm:col-span-2">
                        <div class="max-w-lg flex rounded-md shadow-sm">
                            <textarea name="Tech Specs" rows="3" class="max-w-lg shadow-sm block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border border-gray-300 rounded-md"><?php echo $company['Tech Specs']?></textarea>
                        </div>
                    </div>
                </div>
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200">
                <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                  AI Category type
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                  <div class="max-w-lg flex rounded-md shadow-sm">
                    <input type="text" name="AI Category type" autocomplete="off" value="<?php echo $company['AI Category type']?>" class="flex-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 min-w-0 rounded sm:text-sm border-gray-300">
                  </div>
                </div>
              </div>
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200">
                <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                  Active Products
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                  <div class="max-w-lg flex rounded-md shadow-sm">
                    <input type="text" name="Active Products" autocomplete="off" value="<?php echo $company['Active Products']?>" class="flex-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 min-w-0 rounded sm:text-sm border-gray-300">
                  </div>
                </div>
              </div>
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200">
                <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                  Area Served
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                  <div class="max-w-lg flex rounded-md shadow-sm">
                    <input type="text" name="Area Served" autocomplete="off" value="<?php echo $company['Area Served']?>" class="flex-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 min-w-0 rounded sm:text-sm border-gray-300">
                  </div>
                </div>
              </div>
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200">
                <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                  Base Location
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                  <div class="max-w-lg flex rounded-md shadow-sm">
                    <input type="text" name="Base Location" autocomplete="off" value="<?php echo $company['Base Location']?>" class="flex-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 min-w-0 rounded sm:text-sm border-gray-300">
                  </div>
                </div>
              </div>
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200">
                <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                  Building Models
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                  <div class="max-w-lg flex rounded-md shadow-sm">
                      <textarea name="Building Models" rows="6" class="max-w-lg shadow-sm block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border border-gray-300 rounded-md"><?php echo $company['Building Models']?></textarea>
                  </div>
                </div>
              </div>
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200">
                <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                  Collecting Data
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                  <div class="max-w-lg flex rounded-md shadow-sm">
                      <textarea name="Collecting Data" rows="6" class="max-w-lg shadow-sm block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border border-gray-300 rounded-md"><?php echo $company['Collecting Data']?></textarea>
                  </div>
                </div>
              </div>
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200">
                <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                  Countries ranking visits
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                  <div class="max-w-lg flex rounded-md shadow-sm">
                    <input type="text" name="Countries ranking visits" autocomplete="off" value="<?php echo $company['Countries ranking visits']?>" class="flex-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 min-w-0 rounded sm:text-sm border-gray-300">
                  </div>
                </div>
              </div>
                <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                        Countries ranking visits(JSON)
                    </label>
                    <div class="mt-1 sm:mt-0 sm:col-span-2">
                        <div class="max-w-lg flex rounded-md shadow-sm">
                            <textarea name="Countries ranking visits(JSON)" rows="6" class="max-w-lg shadow-sm block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border border-gray-300 rounded-md"><?php echo isset($company['Countries ranking visits(JSON)']) ? $company['Countries ranking visits(JSON)'] : '';?></textarea>
                        </div>
                    </div>
                </div>
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200">
                <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                  Data Sources
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                  <div class="max-w-lg flex rounded-md shadow-sm">
                      <textarea name="Data Sources" rows="6" class="max-w-lg shadow-sm block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border border-gray-300 rounded-md"><?php echo $company['Data Sources']?></textarea>
                  </div>
                </div>
              </div>
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200">
                <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                  Decisions
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                  <div class="max-w-lg flex rounded-md shadow-sm">
                      <textarea name="Decisions" rows="6" class="max-w-lg shadow-sm block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border border-gray-300 rounded-md"><?php echo $company['Decisions']?></textarea>
                  </div>
                </div>
              </div>
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200">
                <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                  Employee Count
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                  <div class="max-w-lg flex rounded-md shadow-sm">
                    <input type="text" name="Employee Count" autocomplete="off" value="<?php echo $company['Employee Count']?>" class="flex-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 min-w-0 rounded sm:text-sm border-gray-300">
                  </div>
                </div>
              </div>
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200">
                <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                  Features
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                  <div class="max-w-lg flex rounded-md shadow-sm">
                      <textarea name="Features" rows="6" class="max-w-lg shadow-sm block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border border-gray-300 rounded-md"><?php echo $company['Features']?></textarea>
                  </div>
                </div>
              </div>
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200">
                <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                  Founded year
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                  <div class="max-w-lg flex rounded-md shadow-sm">
                    <input type="text" name="Founded year" autocomplete="off" value="<?php echo $company['Founded year']?>" class="flex-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 min-w-0 rounded sm:text-sm border-gray-300">
                  </div>
                </div>
              </div>
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200">
                <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                  Investors
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                  <div class="max-w-lg flex rounded-md shadow-sm">
                      <textarea name="Investors (Decending )" rows="3" class="max-w-lg shadow-sm block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border border-gray-300 rounded-md"><?php echo $company['Investors (Decending )']?></textarea>
                  </div>
                </div>
              </div>
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200">
                <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                  Lead Investors
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                  <div class="max-w-lg flex rounded-md shadow-sm">
                    <input type="text" name="Lead Investors" autocomplete="off" value="<?php echo $company['Lead Investors']?>" class="flex-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 min-w-0 rounded sm:text-sm border-gray-300">
                  </div>
                </div>
              </div>
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200">
                <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                  Live Evaluation and Monitoring
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                  <div class="max-w-lg flex rounded-md shadow-sm">
                      <textarea name="Live Evaluation and Monitoring" rows="6" class="max-w-lg shadow-sm block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border border-gray-300 rounded-md"><?php echo $company['Live Evaluation and Monitoring']?></textarea>
                  </div>
                </div>
              </div>
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200">
                <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                  ML Task
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                  <div class="max-w-lg flex rounded-md shadow-sm">
                      <textarea name="ML Task" rows="6" class="max-w-lg shadow-sm block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border border-gray-300 rounded-md"><?php echo $company['ML Task']?></textarea>
                  </div>
                </div>
              </div>
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200">
                <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                  Making Predictions
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                  <div class="max-w-lg flex rounded-md shadow-sm">
                      <textarea name="Making Predictions" rows="6" class="max-w-lg shadow-sm block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border border-gray-300 rounded-md"><?php echo $company['Making Predictions']?></textarea>
                  </div>
                </div>
              </div>
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200">
                <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                  Monthly Visit growth
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                  <div class="max-w-lg flex rounded-md shadow-sm">
                    <input type="text" name="Monthly Visit growth" autocomplete="off" value="<?php echo $company['Monthly Visit growth']?>" class="flex-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 min-w-0 rounded sm:text-sm border-gray-300">
                  </div>
                </div>
              </div>
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200">
                <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                  Monthly Visits
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                  <div class="max-w-lg flex rounded-md shadow-sm">
                    <input type="text" name="Monthly Visits" autocomplete="off" value="<?php echo $company['Monthly Visits']?>" class="flex-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 min-w-0 rounded sm:text-sm border-gray-300">
                  </div>
                </div>
              </div>
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200">
                <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                  Number of Investors
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                  <div class="max-w-lg flex rounded-md shadow-sm">
                    <input type="text" name="Number of Investors" autocomplete="off" value="<?php echo $company['Number of Investors']?>" class="flex-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 min-w-0 rounded sm:text-sm border-gray-300">
                  </div>
                </div>
              </div>
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200">
                <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                  Offline Evaluation
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                  <div class="max-w-lg flex rounded-md shadow-sm">
                    <input type="text" name="Offline Evaluation" autocomplete="off" value="<?php echo $company['Focused on']?>" class="flex-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 min-w-0 rounded sm:text-sm border-gray-300">
                  </div>
                </div>
              </div>
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200">
                <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                  Product Category
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                  <div class="max-w-lg flex rounded-md shadow-sm">
                    <input type="text" name="Prodct Category" autocomplete="off" value="<?php echo $company['Prodct Category']?>" class="flex-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 min-w-0 rounded sm:text-sm border-gray-300">
                  </div>
                </div>
              </div>
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200">
                <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                  Total funding Amount
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                  <div class="max-w-lg flex rounded-md shadow-sm">
                    <input type="text" name="Total funding Amount" autocomplete="off" value="<?php echo $company['Total funding Amount']?>" class="flex-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 min-w-0 rounded sm:text-sm border-gray-300">
                  </div>
                </div>
              </div>
              <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200">
                <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                  Value Propositions
                </label>
                <div class="mt-1 sm:mt-0 sm:col-span-2">
                  <div class="max-w-lg flex rounded-md shadow-sm">
                     <textarea name="Value Propositions" rows="6" class="max-w-lg shadow-sm block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border border-gray-300 rounded-md"><?php echo $company['Value Propositions']?></textarea>
                  </div>
                </div>
              </div>    
            </div>
            
            <div class="pt-5">
              <div class="flex justify-end">
                <a href="/backpanel/backpanel-company-list.phpy-list.php" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500  disabled:opacity-50 disabled:cursor-not-allowed">
                  Cancel
                </a>
                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500  disabled:opacity-50 disabled:cursor-not-allowed">
                  Save
                </button>
              </div>
            </div>
            
          </div>
        </form>
      </div>
    </div>
    
  </div>

  <?php include '../partials/notification.php' ?>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="../dist/js/main.js"></script>
  <script>
      if ( window.history.replaceState ) {
          window.history.replaceState( null, null, window.location.href );
      }
  </script>
</body>
</html>