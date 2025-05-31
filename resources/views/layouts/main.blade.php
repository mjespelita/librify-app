
<!DOCTYPE html>
<html lang='{{ str_replace('_', '-', app()->getLocale()) }}'>
    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <meta name='csrf-token' content='{{ csrf_token() }}'>
        <meta name='author' content='Mark Jason Penote Espelita'>
        <meta name='keywords' content='Inventory Management System, IMS, ISP'>
        <meta name='description' content='Efficient inventory management system for Librify IT Solutions, designed to streamline operations, track network equipment, and optimize resource allocation to ensure seamless service delivery and reduce operational costs.'>

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link href='{{ url('assets/bootstrap/bootstrap.min.css') }}' rel='stylesheet'>
        <!-- FontAwesome for icons -->
        <link href='{{ url('assets/font-awesome/css/all.min.css') }}' rel='stylesheet'>
        <link rel='stylesheet' href='{{ url('assets/custom/style.css') }}'>
        <link rel='icon' href='{{ url('assets/logo.png') }}'>
    </head>
    <body class='font-sans antialiased'>

        <!-- Sidebar for Desktop View -->
        <div class='sidebar' id='mobileSidebar'>
            <div class='logo'>
                <div class="p-3">
                    <img src='{{ url('assets/logo.png') }}' alt=''> <br>
                </div>
            </div>

            @if (Auth::user()->role != 'office_admin')
                <a href='{{ url('dashboard') }}' class='{{ request()->is('dashboard', 'admin-dashboard', 'employee-dashboard') ? 'active' : '' }}'>
                    <i class='fas fa-tachometer-alt'></i> Dashboard
                </a>
            @endif

            @if (Auth::user()->role === 'admin')

                <a href='{{ url('backups') }}' class='{{ request()->is('backups') ? 'active' : '' }}'>
                    <i class='fas fa-database'></i> Backups
                </a>

                <div class="p-3">
                    <b>Inventory Management</b>
                </div>
                <a href='{{ url('items') }}' class='{{ request()->is('items', 'trash-items', 'create-items', 'show-items/*', 'edit-items/*', 'delete-items/*', 'view-add-item-quantity-logs/*', 'create-add-item-quantity/*', 'items-search*') ? 'active' : '' }}'>
                    <i class='fas fa-box'></i> Items
                </a>

                <a href='{{ url('sites') }}' class='{{ request()->is('sites', 'trash-sites', 'create-sites', 'show-sites/*', 'edit-sites/*', 'delete-sites/*', 'sites-search*') ? 'active' : '' }}'>
                    <i class='fas fa-house'></i> Sites
                </a>
                
                <a href='{{ url('types') }}' class='{{ request()->is('types', 'trash-types', 'create-types', 'show-types/*', 'edit-types/*', 'delete-types/*', 'types-search*') ? 'active' : '' }}'>
                    <i class='fas fa-cogs'></i> Types
                </a>

                <a href='{{ url('technicians') }}' class='{{ request()->is('technicians', 'trash-technicians', 'create-technicians', 'show-technicians/*', 'edit-technicians/*', 'delete-technicians/*', 'technicians-search*') ? 'active' : '' }}'>
                    <i class='fas fa-users'></i> Employees
                </a>

                <a href='{{ url('onsites') }}' class='{{ request()->is('onsites', 'view-technician-onsite-items/*', 'trash-onsites', 'create-onsites', 'show-onsites/*', 'edit-onsites/*', 'delete-onsites/*', 'onsites-search*') ? 'active' : '' }}'>
                    <i class='fas fa-house'></i> On Site Items
                </a>

                <a href='{{ url('damages') }}' class='{{ request()->is('damages', 'view-technician-damage-items/*', 'trash-damages', 'create-damages', 'show-damages/*', 'edit-damages/*', 'delete-damages/*', 'damages-search*') ? 'active' : '' }}'>
                    <i class='fas fa-exclamation-triangle'></i> Damaged Items
                </a>

                <a href='{{ url('itemlogs') }}' class='{{ request()->is('itemlogs', 'trash-itemlogs', 'create-itemlogs', 'show-itemlogs/*', 'edit-itemlogs/*', 'delete-itemlogs/*', 'itemlogs-search*') ? 'active' : '' }}'>
                    <i class='fas fa-bars'></i> Item Logs
                </a>
                
                <a href='{{ url('logs') }}' class='{{ request()->is('logs', 'create-logs', 'show-logs/*', 'edit-logs/*', 'delete-logs/*', 'logs-search*') ? 'active' : '' }}'>
                    <i class='fas fa-clipboard-list'></i> Logs
                </a>
                
                <div class="p-3">
                    <b>Task Management</b>
                </div>

                <a href='{{ url('workspaces') }}' 
                class='{{ request()->is('workspaces', 'trash-workspaces', 'create-workspaces', 'show-workspaces/*', 'edit-workspaces/*', 'delete-workspaces/*', 'workspaces-search*') ? 'active' : '' }}'>
                <i class='fas fa-building'></i> Workspaces
                </a>

                <a href='{{ url('projects') }}' 
                class='{{ request()->is('projects', 'trash-projects', 'create-projects', 'show-projects/*', 'edit-projects/*', 'delete-projects/*', 'projects-search*') ? 'active' : '' }}'>
                <i class='fas fa-folder'></i> Projects
                </a>

                <a href='{{ url('tasks') }}' 
                class='{{ request()->is('tasks', 'trash-tasks', 'create-tasks', 'show-tasks/*', 'edit-tasks/*', 'delete-tasks/*', 'tasks-search*') ? 'active' : '' }}'>
                <i class='fas fa-tasks'></i> Tasks
                </a>

                <a href='{{ url('notifications') }}' 
                class='{{ request()->is('notifications', 'trash-notifications', 'create-notifications', 'show-notifications/*', 'edit-notifications/*', 'delete-notifications/*', 'notifications-search*') ? 'active' : '' }}'>
                <i class='fas fa-bell'></i> Notifications
                </a>

                <div class="p-3">
                    <b>Omada API</b>
                </div>

                <a href='{{ url('omada-customers') }}' class='{{ request()->is('omada-customers') ? 'active' : '' }}'>
                    <i class='fas fa-users'></i> Omada Customers
                </a>

                <a href='{{ url('omada-sites') }}' class='{{ request()->is('omada-sites') ? 'active' : '' }}'>
                    <i class='fas fa-users'></i> Omada Sites
                </a>

                <a href='{{ url('omada-audit-logs') }}' class='{{ request()->is('omada-audit-logs') ? 'active' : '' }}'>
                    <i class='fas fa-bars'></i> Omada Audit Logs
                </a>
            @endif

            @if (Auth::user()->role === 'warehouse_admin')

                <div class="p-3">
                    <b>Inventory Management</b>
                </div>
                <a href='{{ url('items') }}' class='{{ request()->is('items', 'trash-items', 'create-items', 'show-items/*', 'edit-items/*', 'delete-items/*', 'view-add-item-quantity-logs/*', 'create-add-item-quantity/*', 'items-search*') ? 'active' : '' }}'>
                    <i class='fas fa-box'></i> Items
                </a>

                <a href='{{ url('sites') }}' class='{{ request()->is('sites', 'trash-sites', 'create-sites', 'show-sites/*', 'edit-sites/*', 'delete-sites/*', 'sites-search*') ? 'active' : '' }}'>
                    <i class='fas fa-house'></i> Sites
                </a>
                
                <a href='{{ url('types') }}' class='{{ request()->is('types', 'trash-types', 'create-types', 'show-types/*', 'edit-types/*', 'delete-types/*', 'types-search*') ? 'active' : '' }}'>
                    <i class='fas fa-cogs'></i> Types
                </a>

                <a href='{{ url('technicians') }}' class='{{ request()->is('technicians', 'trash-technicians', 'create-technicians', 'show-technicians/*', 'edit-technicians/*', 'delete-technicians/*', 'technicians-search*') ? 'active' : '' }}'>
                    <i class='fas fa-users'></i> Employees
                </a>

                <a href='{{ url('onsites') }}' class='{{ request()->is('onsites', 'view-technician-onsite-items/*', 'trash-onsites', 'create-onsites', 'show-onsites/*', 'edit-onsites/*', 'delete-onsites/*', 'onsites-search*') ? 'active' : '' }}'>
                    <i class='fas fa-house'></i> On Site Items
                </a>

                <a href='{{ url('damages') }}' class='{{ request()->is('damages', 'view-technician-damage-items/*', 'trash-damages', 'create-damages', 'show-damages/*', 'edit-damages/*', 'delete-damages/*', 'damages-search*') ? 'active' : '' }}'>
                    <i class='fas fa-exclamation-triangle'></i> Damaged Items
                </a>

                <a href='{{ url('itemlogs') }}' class='{{ request()->is('itemlogs', 'trash-itemlogs', 'create-itemlogs', 'show-itemlogs/*', 'edit-itemlogs/*', 'delete-itemlogs/*', 'itemlogs-search*') ? 'active' : '' }}'>
                    <i class='fas fa-bars'></i> Item Logs
                </a>
                
                <a href='{{ url('logs') }}' class='{{ request()->is('logs', 'create-logs', 'show-logs/*', 'edit-logs/*', 'delete-logs/*', 'logs-search*') ? 'active' : '' }}'>
                    <i class='fas fa-clipboard-list'></i> Logs
                </a>
                
                <div class="p-3">
                    <b>Task Management</b>
                </div>

                <a href='{{ url('workspaces') }}' 
                class='{{ request()->is('workspaces', 'trash-workspaces', 'create-workspaces', 'show-workspaces/*', 'edit-workspaces/*', 'delete-workspaces/*', 'workspaces-search*') ? 'active' : '' }}'>
                <i class='fas fa-building'></i> Workspaces
                </a>

                <a href='{{ url('projects') }}' 
                class='{{ request()->is('projects', 'trash-projects', 'create-projects', 'show-projects/*', 'edit-projects/*', 'delete-projects/*', 'projects-search*') ? 'active' : '' }}'>
                <i class='fas fa-folder'></i> Projects
                </a>

                <a href='{{ url('tasks') }}' 
                class='{{ request()->is('tasks', 'trash-tasks', 'create-tasks', 'show-tasks/*', 'edit-tasks/*', 'delete-tasks/*', 'tasks-search*') ? 'active' : '' }}'>
                <i class='fas fa-tasks'></i> Tasks
                </a>

                <a href='{{ url('notifications') }}' 
                class='{{ request()->is('notifications', 'trash-notifications', 'create-notifications', 'show-notifications/*', 'edit-notifications/*', 'delete-notifications/*', 'notifications-search*') ? 'active' : '' }}'>
                <i class='fas fa-bell'></i> Notifications
                </a>
            @endif

            @if (Auth::user()->role === 'office_admin')
                
                <div class="p-3">
                    <b>Task Management</b>
                </div>

                <a href='{{ url('workspaces') }}' 
                class='{{ request()->is('workspaces', 'trash-workspaces', 'create-workspaces', 'show-workspaces/*', 'edit-workspaces/*', 'delete-workspaces/*', 'workspaces-search*') ? 'active' : '' }}'>
                <i class='fas fa-building'></i> Workspaces
                </a>

                <a href='{{ url('projects') }}' 
                class='{{ request()->is('projects', 'trash-projects', 'create-projects', 'show-projects/*', 'edit-projects/*', 'delete-projects/*', 'projects-search*') ? 'active' : '' }}'>
                <i class='fas fa-folder'></i> Projects
                </a>

                <a href='{{ url('tasks') }}' 
                class='{{ request()->is('tasks', 'trash-tasks', 'create-tasks', 'show-tasks/*', 'edit-tasks/*', 'delete-tasks/*', 'tasks-search*') ? 'active' : '' }}'>
                <i class='fas fa-tasks'></i> Tasks
                </a>

                <a href='{{ url('notifications') }}' 
                class='{{ request()->is('notifications', 'trash-notifications', 'create-notifications', 'show-notifications/*', 'edit-notifications/*', 'delete-notifications/*', 'notifications-search*') ? 'active' : '' }}'>
                <i class='fas fa-bell'></i> Notifications
                </a>
            @endif

            @if (Auth::user()->role === 'technician')

                <div class="p-3">
                    <b>Inventory Management</b>
                </div>
                <a href='{{ url('my-sites') }}' class='{{ request()->is('my-sites', 'trash-sites', 'create-sites', 'show-sites/*', 'edit-sites/*', 'delete-sites/*', 'sites-search*') ? 'active' : '' }}'>
                    <i class='fas fa-house'></i> My Sites
                </a>

                <a href='{{ url('my-onsite-items/'.Auth::user()->id) }}' class='{{ request()->is('my-onsite-items/*', 'view-my-onsite-items-on-site/*', 'trash-onsites', 'create-my-onsite-items', 'show-my-onsite-items/*', 'edit-my-onsite-items/*', 'delete-my-onsite-items/*', 'my-onsite-items-search*') ? 'active' : '' }}'>
                    <i class='fas fa-house'></i> My On Site Items
                </a>

                <a href='{{ url('my-damaged-items/'.Auth::user()->id) }}' class='{{ request()->is('my-damaged-items/*', 'view-my-damaged-items-on-site/*', 'trash-damages', 'create-damages', 'show-damages/*', 'edit-damages/*', 'delete-damages/*', 'damages-search*') ? 'active' : '' }}'>
                    <i class='fas fa-exclamation-triangle'></i> My Damaged Items
                </a>

                <div class="p-3">
                    <b>Task Management</b>
                </div>

                <a href='{{ url('my-tasks') }}' 
                    class='{{ request()->is('my-tasks', 'show-tasks/*', 'trash-my-tasks', 'create-my-tasks', 'show-my-tasks/*', 'edit-my-tasks/*', 'delete-my-tasks/*', 'my-tasks-search*') ? 'active' : '' }}'>
                    <i class='fas fa-tasks'></i> My Tasks
                </a>

                <a href='{{ url('notifications') }}' 
                class='{{ request()->is('notifications', 'trash-notifications', 'create-notifications', 'show-notifications/*', 'edit-notifications/*', 'delete-notifications/*', 'notifications-search*') ? 'active' : '' }}'>
                <i class='fas fa-bell'></i> Notifications
                </a>

            @endif  

            @if (Auth::user()->role === 'employee')

                <div class="p-3">
                    <b>Task Management</b>
                </div>

                <a href='{{ url('my-tasks') }}' 
                    class='{{ request()->is('my-tasks', 'show-tasks/*', 'trash-my-tasks', 'create-my-tasks', 'show-my-tasks/*', 'edit-my-tasks/*', 'delete-my-tasks/*', 'my-tasks-search*') ? 'active' : '' }}'>
                    <i class='fas fa-tasks'></i> My Tasks
                </a>

                <a href='{{ url('notifications') }}' 
                class='{{ request()->is('notifications', 'trash-notifications', 'create-notifications', 'show-notifications/*', 'edit-notifications/*', 'delete-notifications/*', 'notifications-search*') ? 'active' : '' }}'>
                <i class='fas fa-bell'></i> Notifications
                </a>

            @endif  

            <hr>

            <a href='{{ url('release-notes.html') }}'>
                <i class='fas fa-book'></i> Release Notes
            </a>
            
            <a href='{{ url('user/profile') }}'><i class='fas fa-user'></i> {{ Auth::user()->name }}</a>
        </div>

        <!-- Top Navbar -->
        <nav class='navbar navbar-expand-lg navbar-dark'>
            <div class='container-fluid'>
                <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarNav'
                    aria-controls='navbarNav' aria-expanded='false' aria-label='Toggle navigation' onclick='toggleSidebar()'>
                    <i class='fas fa-bars'></i>
                </button>
            </div>
        </nav>

        <x-main-notification />

        <div class='content'>
            @yield('content')
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

        {{-- apex charts --}}

        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

        <!-- Bootstrap JS and dependencies -->
        <script src='{{ url('assets/bootstrap/bootstrap.bundle.min.js') }}'></script>

        <!-- Custom JavaScript -->
        <script src="{{ url('assets/custom/script.js') }}"></script>
        <script>
            function toggleSidebar() {
                document.getElementById('mobileSidebar').classList.toggle('active');
                document.getElementById('sidebar').classList.toggle('active');
            }
        </script>

<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular.min.js"></script>

<script>
   (function() {
     const style = document.createElement('style');
     style.textContent = `
       .chat-toggle-btn {
         position: fixed;
         bottom: 20px;
         right: 20px;
         z-index: 1000;
         background: #F88B22;
         color: white;
         border: none;
         border-radius: 50%;
         width: 60px;
         height: 60px;
         font-size: 24px;
         cursor: pointer;
         box-shadow: 0 4px 8px rgba(0,0,0,0.2);
       }
       .chatbox-wrapper {
         position: fixed;
         bottom: 90px;
         right: 20px;
         width: 100%;
         max-width: 30%;
         height: 60%;
         background: white;
         border-radius: 15px;
         box-shadow: 0 0 20px rgba(0,0,0,0.2);
         display: none;
         flex-direction: column;
         z-index: 1000;
         overflow: hidden;
       }
       .chatbox-header {
         background: #F88B22;
         color: white;
         padding: 10px;
         font-weight: bold;
         text-align: center;
       }
       .chatbox-messages {
         flex: 1;
         overflow-y: auto;
         padding: 10px;
         display: flex;
         flex-direction: column;
       }
       .chatbox-message {
         margin: 5px 0;
         padding: 8px 12px;
         border-radius: 10px;
         max-width: 80%;
         white-space: pre-wrap;
       }
       .chatbox-message.user {
         background: #F88B22;
         align-self: flex-end;
         color: #fff;
       }
       .chatbox-message.bot {
         background: #e2e3e5;
         align-self: flex-start;
       }
       .chatbox-footer {
         display: flex;
         padding: 10px;
         gap: 5px;
         border-top: 1px solid #eee;
         background: white;
       }
       .chatbox-footer input {
         flex: 1;
         padding: 6px 10px;
         border: 1px solid #ccc;
         border-radius: 5px;
       }
       .chatbox-footer button {
         background: #F88B22;
         color: white;
         border: none;
         padding: 6px 12px;
         border-radius: 5px;
         cursor: pointer;
       }
       .chatbox-typing {
         font-style: italic;
         color: gray;
         padding: 4px 10px;
       }
       .dropdown {
         padding: 10px;
       }
       .dropdown-menu.show {
         display: block;
         position: static;
         float: none;
       }
       .dropdown-item {
         cursor: pointer;
       }
   
       @media (max-width: 480px) {
        .chatbox-wrapper {
        max-width: 95vw;
        height: 80vh;

        bottom: 80px;
        right: 10px;
        border-radius: 10px;
        }
        .chat-toggle-btn {
        width: 50px;
        height: 50px;
        font-size: 20px;
        bottom: 20px;
        right: 15px;
        }
    }
     `;
     document.head.appendChild(style);
   
     const chatButton = document.createElement('button');
     chatButton.className = 'chat-toggle-btn';
     chatButton.innerHTML = '&#128172;';
     document.body.appendChild(chatButton);
   
     const chatContainer = document.createElement('div');
     chatContainer.className = 'chatbox-wrapper';
     
     const faqData = [
        {
            question: "How to add an item?",
            response: "How to add an item \n\n1. Navigate to the Items page \n2. Click the 'Add Item' button on the top-right corner of the page and fill out the required fields. \n\nRemember: Determine first whether the item has a serial number or not before proceeding."
        },
        {
            question: "How to edit/update an item?",
            response: "How to edit/update an item \n\n1. Click the edit icon. \nNote: Items with serial numbers and those without are handled differently. If you want to decrease the quantity of an item with a serial number, click the specific serial number you want to remove—its quantity will decrease. For items without a serial number, simply update their quantity."
        },
        {
            question: "How to delete an item?",
            response: "To delete an item, use the delete icon next to it."
        },
        {
            question: "Where to find settings?",
            response: "Settings can be found under the top-right user menu."
        }
    ];

    // Generate dropdown HTML from JSON
    const dropdownHTML = faqData.map(item => 
    `<li><a class="dropdown-item" href="#" data-question="${item.question}" data-response="${item.response}">${item.question}</a></li>`
    ).join('');

    // Insert HTML into chat container
    chatContainer.innerHTML = `
    <div ng-app="chatApp" ng-controller="ChatController" style="display: flex; flex-direction: column; height: 100%;">
        <div class="chatbox-header">Librify / GPT-3 [Experimental]</div>
        <div class="chatbox-messages" id="chatboxMessages">
        <div ng-if="!beginConversation" class="no-chat-placeholder text-center text-muted my-3">
            <i class="fas fa-robot fa-2x d-block mb-2"></i>
            <small>
                This is the Librify Automated Chatbot together with the GPT-3 model. <br>
                For system-related questions, click "Ask question about the system." <br>
                For a general AI conversation, simply type your message below. <br>
                Note: GPT-3 does not have knowledge about our specific system.
            </small>
        </div>
        <div ng-repeat="msg in messages" class="chatbox-message" ng-class="msg.sender">[[ msg.text ]]</div>
        <div class="chatbox-typing" ng-show="isTyping">Bot is typing...</div>
        </div>
        <div class="dropdown">
        <input class="form-control mb-2" id="dropdownSearch" type="text" placeholder="Ask question about the system...">
        <ul class="dropdown-menu show w-100" id="customDropdown" style="display:none;">
            ${dropdownHTML}
        </ul>
        </div>
        <form ng-submit="sendMessage()" class="chatbox-footer">
        <input type="text" ng-model="userInput" placeholder="Send a message to GPT-3..." required>
        <button type="submit">Send</button>
        </form>
    </div>
    `;



     document.body.appendChild(chatContainer);
   
     chatButton.addEventListener('click', () => {
       chatContainer.style.display = chatContainer.style.display === 'flex' ? 'none' : 'flex';
       const msgBox = document.getElementById('chatboxMessages');
       setTimeout(() => {
         msgBox.scrollTop = msgBox.scrollHeight;
       }, 100);
     });
   
     const app = angular.module('chatApp', []);
   
     app.config(['$interpolateProvider', function($interpolateProvider) {
       $interpolateProvider.startSymbol('[[');
       $interpolateProvider.endSymbol(']]');
     }]);
   
     app.controller('ChatController', ['$scope', '$http', '$timeout', function($scope, $http, $timeout) {
       $scope.messages = [];
       $scope.userInput = '';
       $scope.isTyping = false;

       $scope.beginConversation = false;
   
       const scrollToBottom = () => {
         $timeout(() => {
           const container = document.getElementById('chatboxMessages');
           container.scrollTop = container.scrollHeight;
         }, 50);
       };
   
       $scope.sendMessage = function() {

        $scope.beginConversation = true;

         const input = $scope.userInput.trim();
         if (!input) return;
   
         $scope.messages.push({ text: input, sender: 'user' });
         $scope.userInput = '';
         $scope.isTyping = true;
         scrollToBottom();
   
         $http.get('https://text.pollinations.ai/' + encodeURIComponent(input), {
           headers: { 'Content-Type': 'text/plain' }
         }).then(function(response) {
           const fullText = response.data;
           let typedText = '';
           let i = 0;
   
           function typeChar() {
             if (i < fullText.length) {
               typedText += fullText.charAt(i);
               $timeout(() => {
                 if ($scope.messages[$scope.messages.length - 1].sender !== 'bot') {
                   $scope.messages.push({ text: '', sender: 'bot' });
                 }
                 $scope.messages[$scope.messages.length - 1].text = typedText;
                 scrollToBottom();
                 i++;
                 typeChar();
               }, 20);
             } else {
               $scope.isTyping = false;
             }
           }
   
           typeChar();
         }).catch(function(error) {
           $scope.isTyping = false;
           $scope.messages.push({ text: 'Error: Something went wrong, please check your internet connection and try again.', sender: 'bot' });
           scrollToBottom();
         });
       };
     }]);
   
     angular.bootstrap(chatContainer, ['chatApp']);
   
     // Dropdown search & select logic
     const dropdownSearch = document.getElementById('dropdownSearch');
     const dropdownItems = document.querySelectorAll('#customDropdown .dropdown-item');
   
     dropdownSearch.addEventListener('input', function () {
        document.getElementById('customDropdown').style.display = 'block';
       const filter = this.value.toLowerCase();
       dropdownItems.forEach(item => {
         const text = item.textContent.toLowerCase();
         item.style.display = text.includes(filter) ? 'block' : 'none';
       });
     });
   
     dropdownItems.forEach(item => {
   item.addEventListener('click', function (e) {

    const scopeElBeginConversation = document.querySelector('[ng-controller="ChatController"]');
    if (scopeElBeginConversation) {
        const scope = angular.element(scopeElBeginConversation).scope();
        scope.$apply(() => {
            scope.beginConversation = true;
        });
    }

    e.preventDefault();
    document.getElementById('customDropdown').style.display = 'none';
   
    const question = this.getAttribute('data-question');
    const response = this.getAttribute('data-response');
   
    const scopeEl = document.querySelector('[ng-controller="ChatController"]');
    if (scopeEl) {
      const scope = angular.element(scopeEl).scope();
      scope.$apply(() => {
        scope.messages = scope.messages || [];
        scope.messages.push({ text: question, sender: 'user' });
        scope.isTyping = true;
      });
   
      let typedText = '';
        let i = 0;
   
        function typeChar() {
        if (i < response.length) {
            typedText += response.charAt(i);
            setTimeout(() => {
            scope.$apply(() => {
                const messages = scope.messages;
                if (!messages.length || messages[messages.length - 1].sender !== 'bot') {
                messages.push({ text: '', sender: 'bot' });
                }
                messages[messages.length - 1].text = typedText;
                scope.isTyping = true;
   
                const container = document.getElementById('chatboxMessages');
                if (container) container.scrollTop = container.scrollHeight;
                i++;
                typeChar();
            });
            }, 20);
        } else {
            // Delay slightly to ensure DOM updates settle before marking as done
            setTimeout(() => {
            scope.$apply(() => {
                scope.isTyping = false;
            });
            }, 50);
        }
        }
   
        typeChar();
   
    }
   
    dropdownSearch.value = '';
   });
   });
   
   })();
</script>
    </body>
</html>
