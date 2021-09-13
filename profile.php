<?php
require_once './imports/firebase.php';

// If id is not present return to the list
if( ! isset($_GET['company_id'])){
    header('Location:/new/index.php', true);
    exit;
}

$companyRef = $database->getReference('/' . $_GET['company_id']);
$company = $companyRef->getSnapshot()->getValue();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>intelBiz</title>

    <link rel="stylesheet" href="./dist/css/styles.css">
</head>
<body>
  <div class="relative bg-gray-800 overflow-hidden">
    <div class="relative py-6">
      <div>
        <nav class="relative max-w-7xl mx-auto flex items-center justify-between px-4 sm:px-6" aria-label="Global">
          <div class="flex items-center flex-1">
            <div class="flex items-center justify-between w-full md:w-auto">
              <a href="#">
                <img class="h-8 w-auto sm:h-10" src="./dist/images/log.svg" alt="">
              </a>
            </div>
          </div>
        </nav>
      </div>
    </div>
  </div>

  <div class="bg-gray-100">
    <div class="max-w-4xl mx-auto px-4 py-16 sm:px-6 sm:pt-20 sm:pb-24 lg:max-w-7xl lg:px-8">

      <!-- Page header -->
      <div class="max-w-3xl mx-auto px-4 sm:px-6 md:flex md:items-center md:justify-between md:space-x-5 lg:max-w-7xl lg:px-8">
        <div class="flex items-center space-x-5">
          <div class="flex-shrink-0">
            <div class="relative">
                <?php if(isset($company['Logo Image'])){ ?>
                    <img class="w-32 h-32 flex-shrink-0 mx-auto bg-black rounded-full" src="<?php echo $company['Logo Image']?>" alt="">
                <?php }else{ ?>
                    <img class="h-16 w-16 rounded-full" src="https://images.unsplash.com/photo-1534237710431-e2fc698436d0?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=8&w=1024&h=1024&q=80" alt="">
                <?php }?>
              <span class="absolute inset-0 shadow-inner rounded-full" aria-hidden="true"></span>
            </div>
          </div>
          <div>
            <h1 class="text-2xl font-bold text-gray-900"><?php echo $company['Company Name'];?></h1>
            <p class="text-sm font-medium text-gray-500"><?php echo ucfirst($company['AI Category type']);?></p>
          </div>
        </div>
        <div class="mt-6 flex flex-col-reverse justify-stretch space-y-4 space-y-reverse sm:flex-row-reverse sm:justify-end sm:space-x-reverse sm:space-y-0 sm:space-x-3 md:mt-0 md:flex-row md:space-x-3">
            <button onclick="history.back();" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500">
              Back to Search
            </button>
          </div>
      </div>

      <div class="mt-8 max-w-3xl mx-auto grid grid-cols-1 gap-6 sm:px-6 lg:max-w-7xl lg:grid-flow-col-dense lg:grid-cols-3">
        <div class="space-y-6 lg:col-start-1 lg:col-span-2">
          <!-- Description list-->
          <section aria-labelledby="applicant-information-title">
            <div class="bg-white shadow sm:rounded-lg">
              <div class="px-4 py-5 sm:px-6">
                <h2 id="applicant-information-title" class="text-lg leading-6 font-medium text-gray-900">
                  General Information
                </h2>
              </div>
              <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                  <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">
                        AI Category
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <?php echo ucfirst($company['AI Category type']);?>
                    </dd>
                  </div>
                  <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">
                      Business Type
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <?php echo $company['Business type'];?>
                    </dd>
                  </div>
                  <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">
                      Base Location
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <?php echo $company['Base Location'];?>
                    </dd>
                  </div>
                  <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">
                        Area Served
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <?php echo $company['Area Served'];?>
                    </dd>
                  </div>
                    <?php if($company['Website']){?>
                  <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">
                      Website
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <a href="<?php echo $company['Website'];?>"><?php echo $company['Website'];?></a>
                    </dd>
                  </div>
                    <?php } ?>
                    <?php if(isset($company['Focused on'])){?>
                      <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">
                          Focused On
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <?php echo $company['Focused on'];?>
                        </dd>
                      </div>
                    <?php }?>
                    <?php if(isset($company['Features'])){?>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">
                                Features
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <?php echo $company['Features'];?>
                            </dd>
                        </div>
                    <?php }?>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">
                                Tags
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <?php echo $company['sub tags'];?>
                            </dd>
                        </div>

                    <?php if(isset($company['About'])){?>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">
                                About
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <?php echo $company['About'];?>
                            </dd>
                        </div>
                    <?php }?>
                </dl>
              </div>
            </div>
          </section>

            <section aria-labelledby="applicant-information-title">
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h2 id="applicant-information-title" class="text-lg leading-6 font-medium text-gray-900">
                            Investments
                        </h2>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">
                                    Lead Investor
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <?php echo $company['Lead Investors'];?>
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">
                                    Number of Investors
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <?php echo $company['Number of Investors'];?>
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">
                                    Founded Year
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <?php echo $company['Founded year'];?>
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">
                                    Total Funding Amount
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <?php echo $company['Total funding Amount'];?>
                                </dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">
                                    Investors
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <?php echo $company['Investors (Decending )'];?>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </section>

            <section aria-labelledby="applicant-information-title">
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h2 id="applicant-information-title" class="text-lg leading-6 font-medium text-gray-900">

                        </h2>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">
                                    Collecting Data
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <?php echo $company['Collecting Data'];?>
                                </dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">
                                    Data Sources
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <?php echo $company['Data Sources'];?>
                                </dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">
                                    Decisions
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <?php echo $company['Decisions'];?>
                                </dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">
                                    ML Task
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <?php echo $company['ML Task'];?>
                                </dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">
                                    Making Predictions
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <?php echo $company['Making Predictions'];?>
                                </dd>
                            </div>
                        <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">
                                    Offline Evaluation
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <?php echo $company['Offline Evaluation'];?>
                                </dd>
                            </div>
                        <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">
                                    Value Propositions
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <?php echo $company['Value Propositions'];?>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </section>
        </div>

        <section class="lg:col-start-3 lg:col-span-1">
          <div class="bg-white px-4 py-5 shadow sm:rounded-lg sm:px-6">
            <div class="flow-root">
                <div class="mb-5 flex justify-start items-center">
                    <dt class="text-sm font-medium text-gray-500">
                        Employee Count:
                    </dt>
                    <dd class="ml-4 text-sm text-gray-900">
                        <?php echo $company['Employee Count'];?>
                    </dd>
                </div>
                <?php if(isset($company['Tech Specs']) && ! empty($company['Tech Specs'])){ ?>
                <div class="mb-5 flex justify-start items-center border-t pt-5">
                    <dt class="text-sm font-medium text-gray-500">
                        Tech Specs:
                    </dt>
                    <dd class="ml-4 text-sm text-gray-900">
                        <?php echo $company['Tech Specs'];?>
                    </dd>
                </div>
                <?php } ?>
                <div class="mb-5 flex justify-start items-center  border-t pt-5">
                    <dt class="text-sm font-medium text-gray-500">
                        Monthly Visits:
                    </dt>
                    <dd class="ml-4 text-sm text-gray-900">
                        <?php echo $company['Monthly Visits'];?>
                    </dd>
                </div>
                <div class="mb-5 flex justify-start items-center">
                    <dt class="text-sm font-medium text-gray-500">
                        Monthly Visit Growth:
                    </dt>
                    <dd class="ml-4 text-sm text-gray-900">
                        <?php echo $company['Monthly Visit growth'];?>
                    </dd>
                </div>
                <?php if(isset($company['Countries ranking visits(JSON)']) && ! empty($company['Countries ranking visits(JSON)'])){ ?>
                <div class="border-t pt-5">
                    <dt class="text-sm font-medium text-gray-500">
                        Countries' Visits Ranking:
                    </dt>
                    <canvas id="visits-chart" height="300"></canvas>
                </div>
                <?php } ?>
            </div>
          </div>
        </section>
      </div>

    </div>
  </div>

  <footer class="bg-gray-800">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 md:flex md:items-center md:justify-between lg:px-8">
      <div class="flex justify-center space-x-6 md:order-2">
        <a href="#" class="text-gray-400 hover:text-gray-500">
          <span class="sr-only">Facebook</span>
          <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
          </svg>
        </a>

        <a href="#" class="text-gray-400 hover:text-gray-500">
          <span class="sr-only">Instagram</span>
          <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
          </svg>
        </a>

        <a href="#" class="text-gray-400 hover:text-gray-500">
          <span class="sr-only">Twitter</span>
          <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
          </svg>
        </a>

        <a href="#" class="text-gray-400 hover:text-gray-500">
          <span class="sr-only">GitHub</span>
          <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
          </svg>
        </a>

        <a href="#" class="text-gray-400 hover:text-gray-500">
          <span class="sr-only">Dribbble</span>
          <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path fill-rule="evenodd" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10c5.51 0 10-4.48 10-10S17.51 2 12 2zm6.605 4.61a8.502 8.502 0 011.93 5.314c-.281-.054-3.101-.629-5.943-.271-.065-.141-.12-.293-.184-.445a25.416 25.416 0 00-.564-1.236c3.145-1.28 4.577-3.124 4.761-3.362zM12 3.475c2.17 0 4.154.813 5.662 2.148-.152.216-1.443 1.941-4.48 3.08-1.399-2.57-2.95-4.675-3.189-5A8.687 8.687 0 0112 3.475zm-3.633.803a53.896 53.896 0 013.167 4.935c-3.992 1.063-7.517 1.04-7.896 1.04a8.581 8.581 0 014.729-5.975zM3.453 12.01v-.26c.37.01 4.512.065 8.775-1.215.25.477.477.965.694 1.453-.109.033-.228.065-.336.098-4.404 1.42-6.747 5.303-6.942 5.629a8.522 8.522 0 01-2.19-5.705zM12 20.547a8.482 8.482 0 01-5.239-1.8c.152-.315 1.888-3.656 6.703-5.337.022-.01.033-.01.054-.022a35.318 35.318 0 011.823 6.475 8.4 8.4 0 01-3.341.684zm4.761-1.465c-.086-.52-.542-3.015-1.659-6.084 2.679-.423 5.022.271 5.314.369a8.468 8.468 0 01-3.655 5.715z" clip-rule="evenodd" />
          </svg>
        </a>
      </div>
      <div class="mt-8 md:mt-0 md:order-1">
        <p class="text-center text-base text-gray-400">
          &copy; 2021 intelBiz. All rights reserved.
        </p>
      </div>
    </div>
  </footer>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.4.1/dist/chart.min.js"></script>
  <script src="./dist/js/main.js"></script>

  <?php
    $visits = isset($company['Countries ranking visits(JSON)']) ? json_decode($company['Countries ranking visits(JSON)'], true) : [];
    $labels = [];
    $data = [];

    $colors = [
        '#9CA3AF',
        '#F87171',
        '#FBBF24',
        '#34D399',
        '#60A5FA',
        '#A78BFA',
        '#F472B6',
        '#EF4444',
        '#F59E0B',
        '#059669',
        '#4338CA',
        '#DB2777',
    ];

    if($visits){
        $index = 0;
        foreach ($visits as $label=>$value){
            $labels[] = ucfirst($label);
            $data[] = $value;
            $bgColors[] = $colors[$index];

            $index++;
        }

    }
  ?>

  <script>var labels = <?php echo json_encode($labels)?>;</script>
  <script>var data = <?php echo json_encode($data)?>;</script>
  <script>var bgColors = <?php echo json_encode($bgColors)?>;</script>
  <script>
      if(labels.length > 0) {
          var ctx = document.getElementById('visits-chart').getContext('2d');
          var myChart = new Chart(ctx, {
              type: 'doughnut',
              responsive: true,
              maintainAspectRatio: false,
              data: {
                  labels: labels,
                  datasets: [{
                      label: 'Countries Ranking Visits',
                      data: data,
                      backgroundColor: bgColors,
                      hoverOffset: 4
                  }]
              },
              options: {
                  plugins: {
                      legend: {
                          position: 'right'
                      }
                  }
              }
          });
      }
  </script>

</body>
</html>