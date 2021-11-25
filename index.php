<?php
$searchResults = null;

if(isset($_GET['q'])){
  $query = $_GET['q'];

  $filters = array_filter($_GET['filters'], function($filter){
    return $filter ? true : false;
  });

  $filters = count($filters) ? json_encode($filters) : '';
  $filters = str_replace('"', '\"', $filters);

  $algorithm = isset($_GET['algorithm']) ? $_GET['algorithm'] : 'hamming';

  // Run python script
  // Desired format => python3 classifier.py service "{\"ai_category\":\"fraud detection system\"}"
  exec('python3 ./scripts/classifier-' . $algorithm . '.py "'. $query.'" "'. $filters . '"', $searchResults);

  // If response from script is empty, abort
  if( !$searchResults || ! isset($searchResults[0]) || $searchResults[0] == 'no_results'){
    $searchResults = null;
  }else{
    $searchResults = json_decode($searchResults[0], true);
  }
}


// Filters
$aiCategories = [
  'AI for fraudulent transactions , Fraud Detection' => 'Ai For Fraudulent Transactions , Fraud Detection',
  'Artificial intelligence, deep learning' => 'Artificial Intelligence, Deep Learning',
  'Automotiva Artificial Intelligence (AAI)' => 'Automotiva Artificial Intelligence (AAI)',
  'Computer vision, computational imaging' => 'Computer Vision, Computational Imaging',
  'Drone and data company' => 'Drone And Data Company',
  'Edge detection background removal AI' => 'Edge Detection Background Removal AI',
  'Email Prioritizing' => 'Email Prioritizing',
  'Fake review detection' => 'Fake Review Detection',
  'fraud detection system' => 'Fraud Detection System ',
  'Natural Language Processing' => 'Natural Language Processing',
  'NLP and cognitive computing' => 'NLP And Cognitive Computing',
  'Pattern recognition' => 'Pattern Recognition',
  'Process Automation' => 'Process Automation',
  'Product recommendation system' => 'Product Recommendation System',
  'Real Estate Valuation' => 'Real Estate Valuation',
  'Utilizing customer retention' => 'Utilizing Customer Retention',
  'Weather forecasting' => 'Weather Forecasting ',
  'Virtual Assistant' => 'Virtual Assistant',
];

$servedAreas = [
  'United States (Western)' => 'United States (Western)',
  'United States' => 'United States',
  'United states, China, Germany' => 'United states, China, Germany',
  'Worldwide' => 'Worldwide',
  'European Union & Nordic Countries' => 'European Union & Nordic Countries',
];

$businessTypes = [
  'B2B' => 'B2B',
  'B2B/B2C' => 'B2B/B2C',
  'B2B/B2C/C2C' => 'B2B/B2C/C2C',
];

function hasFiltersSet(){
  if(isset($_GET['filters']['ai_category']) && $_GET['filters']['ai_category']){
    return true;
  }

  if(isset($_GET['filters']['area_served']) && $_GET['filters']['area_served']){
    return true;
  }

  if(isset($_GET['filters']['business_type']) && $_GET['filters']['business_type']){
    return true;
  }

  return false;
}
?>

<?php include('./partials/header.php');?>

  <div class="relative bg-gray-800 overflow-hidden">
    <div class="hidden sm:block sm:absolute sm:inset-0" aria-hidden="true">
      <svg class="absolute bottom-0 right-0 transform translate-x-1/2 mb-48 text-gray-700 lg:top-0 lg:mt-28 lg:mb-0 xl:transform-none xl:translate-x-0" width="364" height="384" viewBox="0 0 364 384" fill="none">
        <defs>
          <pattern id="eab71dd9-9d7a-47bd-8044-256344ee00d0" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
            <rect x="0" y="0" width="4" height="4" fill="currentColor" />
          </pattern>
        </defs>
        <rect width="364" height="384" fill="url(#eab71dd9-9d7a-47bd-8044-256344ee00d0)" />
      </svg>
    </div>
    <div class="relative pt-6 pb-16 sm:pb-24">
      <div>
        <nav class="relative max-w-7xl mx-auto flex items-center justify-between px-4 sm:px-6" aria-label="Global">
          <div class="flex items-center flex-1">
            <div class="flex items-center justify-between w-full md:w-auto">
              <a href="/">
                <img class="h-8 w-auto sm:h-10" src="./dist/images/logo.svg" alt="">
              </a>
            </div>
          </div>
        </nav>
      </div>
  
      <main class="mt-16 sm:mt-24">
        <div class="mx-auto max-w-7xl pb-24">
          <div class="lg:grid lg:grid-cols-12 lg:gap-8">
            <div class="px-4 sm:px-6 sm:text-center md:max-w-2xl md:mx-auto lg:col-span-6 lg:text-left lg:flex lg:items-center">
              <div>
            
                <h1 class="mt-4 text-4xl tracking-tight font-extrabold text-white sm:mt-5 sm:leading-none lg:mt-6 lg:text-5xl xl:text-6xl">
                  <span>Intelligence to enrich your</span>
                  <span class="text-indigo-400">business</span>
                </h1>
                <p class="mt-3 text-base text-gray-300 sm:mt-5 sm:text-xl lg:text-lg xl:text-xl">
                  To discover what audiences love, we measure across all channels and platforms⁠ - Catch the most important opportunities and accelerate growth.
                </p>
              </div>
            </div>
            <div class="mt-16 sm:mt-24 lg:mt-0 lg:col-span-6">
              <div class="bg-white sm:max-w-md sm:w-full sm:mx-auto sm:rounded-lg sm:overflow-hidden">
                <div class="px-4 py-8 sm:px-10">
                  <div>
                    <p class="text-sm font-medium text-gray-700">
                      Start with searching your business idea
                    </p>
                  </div>
  
                  <div class="mt-6">
                    <form action="/#search-results" method="GET" class="space-y-6" id="search-form">
                      <div>
                        <input type="text" name="q" value="<?php echo isset($_GET['q']) ? $_GET['q'] : '';?>" id="search" autocomplete="off" class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md">
                      </div>

                      <?php //if(isset($_GET['dev']) && $_GET['dev'] == 'true') {?>
                        <div>
                          <label class="text-sm font-medium text-gray-700">Algorithm <span class="bg-green-500 font-bold inline-flex items-center justify-center leading-none mr-2 px-2 rounded-full text-red-100 text-white text-xs">Dev</span> </label>
                          <select name="algorithm" class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md">
                            <option value="hamming" <?php echo isset($_GET['algorithm']) && $_GET['algorithm'] == 'hamming' ? 'selected' : '' ?>>Hamming</option>
                            <option value="euclidean" <?php echo isset($_GET['algorithm']) && $_GET['algorithm'] == 'euclidean' ? 'selected' : '' ?>>Euclidean</option>
                            <option value="euclidean-beta" <?php echo isset($_GET['algorithm']) && $_GET['algorithm'] == 'euclidean-beta' ? 'selected' : '' ?>>Euclidean(Beta)</option>
                            <option value="levenshtein" <?php echo isset($_GET['algorithm']) && $_GET['algorithm'] == 'levenshtein' ? 'selected' : '' ?>>Levenshtein(Beta)</option>
                            <option value="cosine-similarity" <?php echo isset($_GET['algorithm']) && $_GET['algorithm'] == 'cosine-similarity' ? 'selected' : '' ?>>Cosine Similarity(Beta)</option>
                          </select>
                        </div>
                      <?php// } ?>

                      <div class="mt-2">
                        <a id="search-filters-trigger" href="javascript:void(0)" class="text-sm text-blue-700">Advanced filters</a>
                      </div>
                      <div id="search-filters" style="<?php echo hasFiltersSet() ? '' : 'display: none;'?>">
                        <div class="">
                          <label class="block text-sm font-medium text-gray-700 mb-2">
                            AI Category
                          </label>
                          <div class="mt-1 rounded-md shadow-sm">
                            <select name="filters[ai_category]" class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300">
                              <option value="">All</option>
                              <?php foreach ($aiCategories as $key => $value) {?>
                                <option value="<?php echo $key?>" <?php echo isset($_GET['filters']['ai_category']) && $_GET['filters']['ai_category'] == $key ? 'selected' : ''?>><?php echo $value?></option>
                              <?php }?>
                            
                            </select>
                          </div>
                        </div>

                        <div class="mt-5">
                          <label class="block text-sm font-medium text-gray-700 mb-2">
                            Serving Area
                          </label>
                          <div class="mt-1 rounded-md shadow-sm">
                            <select name="filters[area_served]" class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300">
                              <option value="">All</option>
                              <?php foreach ($servedAreas as $key => $value) {?>
                                <option value="<?php echo $key?>" <?php echo isset($_GET['filters']['area_served']) && $_GET['filters']['area_served'] == $key ? 'selected' : ''?>><?php echo $value?></option>
                              <?php }?>
                              
                            </select>
                          </div>
                        </div>

                        <div class="mt-5">
                          <label class="block text-sm font-medium text-gray-700 mb-2">
                            Business Type
                          </label>
                          <div class="mt-1 rounded-md shadow-sm">
                            <select name="filters[business_type]" class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300">
                              <option value="">All</option>
                              <?php foreach ($businessTypes as $key => $value) {?>
                                <option value="<?php echo $key?>" <?php echo isset($_GET['filters']['business_type']) && $_GET['filters']['business_type'] == $key ? 'selected' : ''?>><?php echo $value?></option>
                              <?php }?>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div>
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                          Search
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

<?php if($searchResults){?>

  <div class="bg-white" id="search-results">
    <div class="max-w-4xl mx-auto px-4 py-16 sm:px-6 sm:pt-20 sm:pb-24 lg:max-w-7xl lg:pt-24 lg:px-8">
      <h2 class="text-3xl font-extrabold text-gray-700 tracking-tight">
        Search results for: <span class="text-indigo-400"><?php echo $_GET['q'];?></span>
      </h2>
      <p class="mt-4 max-w-3xl text-gray-500">
        Here are our suggestions ordered by relativity
      </p>
      <div class="mt-12">
        <ul class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">

          <?php foreach($searchResults as $key=>$result){?>
            <li class="col-span-1 flex flex-col text-center bg-white rounded-lg shadow-lg border divide-y divide-gray-200">
              <div class="flex-1 flex flex-col p-8">
                <?php if(isset($result['image'])){ ?>
                  <img class="w-32 h-32 flex-shrink-0 mx-auto bg-black rounded-full" src="<?php echo $result['image']?>" alt="">
                <?php }else{ ?>
                  <img class="w-32 h-32 flex-shrink-0 mx-auto bg-black rounded-full" src="https://images.unsplash.com/photo-1534237710431-e2fc698436d0?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=8&w=1024&h=1024&q=80" alt="">
                <?php }?>
                <h3 class="mt-6 text-gray-900 text-lg font-bold"><?php echo $result['name']?></h3>
                <dl class="mt-1 flex-grow flex flex-col justify-between">
                  <dd class="text-gray-500 text-sm mb-2">
                    <?php  echo ucfirst($result['ai_category']);?>
                    <span class="font-black">·</span>
                    <?php  echo $result['business_type'];?>
                  </dd>
                  <dd class="flex items-center justify-center text-gray-500 text-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <?php  echo $result['location'];?>
                  </dd>
                  <dd class="mt-6">

                    <?php 
                      $tags = explode(',', $result['tags']);

                      // Sort items by length
                      usort($tags, function ($a,$b){
                        return strlen($b) - strlen($a);
                      });

                      foreach($tags as $key=>$tag){
                        if($key > 2){
                          break;
                        }
                    ?>
                    <span class="px-2 py-1 text-green-800 text-xxs font-medium bg-green-100 rounded-full"><?php echo trim($tag)?></span>
                    <?php } ?>
                  </dd>
                </dl>
              </div>
              <div>
                <div class="-mt-px flex">
                  <a href="/profile.php?company_id=<?php echo $result['id'];?>" class="relative w-0 flex-1 inline-flex items-center justify-center py-4 text-sm text-gray-700 font-medium border border-transparent rounded-br-lg rounded-bl-lg group group-hover:text-white hover:bg-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <span class="ml-3 group-hover:text-white">View Profile</span>
                  </a>
                </div>
              </div>
            </li>
          <?php } ?>
          
        </ul>
      </div>
    </div>
  </div>
<?php } ?>

<?php if(isset($_GET['q']) && ! $searchResults){ ?>
  <div class="bg-white">
    <div class="max-w-4xl mx-auto px-4 py-16 sm:px-6 sm:pt-20 sm:pb-24 lg:max-w-7xl lg:pt-24 lg:px-8">
      <h2 class="text-3xl font-extrabold text-gray-700 tracking-tight">
        Sorry, No results for: <span class="text-indigo-400"><?php echo $_GET['q'];?></span>
      </h2>
      <p class="mt-4 max-w-3xl text-gray-500">
        Try another keyword or search phrase.
      </p>
    </div>
  </div>
<?php } ?>

<div class="bg-green-100">
    <div class="max-w-4xl mx-auto px-4 py-16 sm:px-6 sm:pt-20 sm:pb-24 lg:max-w-7xl lg:pt-24 lg:px-8">
      <h2 class="text-3xl font-extrabold text-gray-700 tracking-tight">
        We provide the best in class intelligence services for the growth of your next business idea.
      </h2>
      <p class="mt-4 text-lg text-gray-500">
        If you'd like more info, 🐰 I suggest you read How to conduct a competitor analysis. Recommended by experts, it's a comprehensive guide explaining the what, why, and how of competitor analysis.
      </p>
      <div class="mt-12 grid grid-cols-1 gap-x-6 gap-y-12 sm:grid-cols-2 lg:mt-16 lg:grid-cols-3 lg:gap-x-8 lg:gap-y-16">
        <div class="bg-gray-50 p-5 rounded-md">
          <div>
            <span class="flex items-center justify-center h-12 w-12 rounded-md bg-purple-50">
              <!-- Heroicon name: outline/inbox -->
              <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
              </svg>
            </span>
          </div>
          <div class="mt-6">
            <h3 class="text-lg font-medium text-gray-700">Inteligent algorithm</h3>
            <p class="mt-2 text-base text-gray-500">
              Identifying and evaluating your competitors. Their strengths and weaknesses. How they compare to your business.
            </p>
          </div>
        </div>

        <div class="bg-gray-50 p-5 rounded-md">
          <div>
            <span class="flex items-center justify-center h-12 w-12 rounded-md bg-purple-50">
              <svg class="h-6 w-6 text-indigo-600" x-description="Heroicon name: outline/document-report" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
            </span>
          </div>
          <div class="mt-6">
            <h3 class="text-lg font-medium text-gray-700">Market analysis</h3>
            <p class="mt-2 text-base text-gray-500">
            What level of brand awareness do your competitors have in your market? More or less than your brand? Find their prices, earnings reports, share prices, customer care best practices, company cuture, distribution, and a whole lot more.
            </p>
          </div>
        </div>

        <div class="bg-gray-50 p-5 rounded-md">
          <div>
            <span class="flex items-center justify-center h-12 w-12 rounded-md bg-purple-50">
              <svg class="h-6 w-6 text-indigo-600" x-description="Heroicon name: outline/chat-alt" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
              </svg>
            </span>
          </div>
          <div class="mt-6">
            <h3 class="text-lg font-medium text-gray-700">Jump Start your Marketing Strategy</h3>
            <p class="mt-2 text-base text-gray-500">
            Use these insights to fine-tune your brand's marketing strategy. To outsmart your competitors.
            </p>
          </div>
        </div>

        <!-- <div class="bg-gray-50 p-5 rounded-md">
          <div>
            <span class="flex items-center justify-center h-12 w-12 rounded-md bg-purple-50">
              <svg class="h-6 w-6 text-indigo-600" x-description="Heroicon name: outline/chat-alt" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
              </svg>
            </span>
          </div>
          <div class="mt-6">
            <h3 class="text-lg font-medium text-gray-700">Business Suggestions</h3>
            <p class="mt-2 text-base text-gray-500">
              Ac tincidunt sapien vehicula erat auctor pellentesque rhoncus. Et magna sit morbi lobortis.
            </p>
          </div>
        </div> -->
      </div>
    </div>
  </div>

  <div id="search-loader" class="fixed flex flex-col items-center justify-center w-full h-full z-10 top-0 backdrop-filter backdrop-blur-md bg-indigo-400 bg-opacity-25" style="display: none;">
    <lord-icon
        src="https://cdn.lordicon.com//msoeawqm.json"
        trigger="loop"
        stroke="100"
        colors="primary:#808aff,secondary:#ffffff"
        style="width:100px;height:100px">
    </lord-icon>
    <h2 class="text-white text-lg">Hold on. We are finding the best matches for you.</h2>
  </div>

<?php include('./partials/footer.php');?>