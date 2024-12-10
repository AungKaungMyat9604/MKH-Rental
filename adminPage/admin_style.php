<style>
    /* styles.css */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

    /* Ensure box-sizing and reset margins/paddings */
    *,
    *::before,
    *::after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    li {
        list-style: none;
    }

    body.main1 {
        font-family: 'Poppins', sans-serif;
    }

    .wrapper {
        display: flex;
        margin-top: 70px;
    }

    .main {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        width: 100%;
        overflow: hidden;
        transition: all 0.35s ease-in-out;
        background-color: rgb(179, 185, 189);
    }

    #sidebar {
        position: fixed;
        height: 100%;
        width: 80px;
        z-index: 1000;
        transition: all .25s ease-in-out;
        background-color: rgb(33, 37, 41);
        display: flex;
        flex-direction: column;
    }

    #sidebar.expand {
        width: 260px;
    }

    @media (min-width: 1026px) {
        .main.expanded {
            margin-left: 260px;
        }

        .main.collapsed {
            margin-left: 80px;
        }
    }

    @media (max-width: 1025px) {

        #sidebar:not(.expand) .sidebar-logo,
        #sidebar:not(.expand) a.sidebar-link span {
            display: none;
        }

        .main {
            margin-left: 80px;
        }

        .main.collapsed {
            margin-left: 80px;
        }
    }

    .breadcrumb-item a {
        text-decoration: underline;
        color: rgb(33, 37, 41);
        font-size: large;
        font-weight: 600;
    }

    .breadcrumb {
        margin-left: 5px;
    }

    .toggle-btn {
        background-color: transparent;
        cursor: pointer;
        border: 0;
        padding: 1rem 1.8rem;
    }

    .toggle-btn i {
        font-size: 1.5rem;
        color: #FFF;
    }

    .sidebar-logo {
        margin: auto 0;
    }

    .sidebar-logo a {
        color: #FFF;
        font-size: 1.15rem;
        font-weight: 600;
        text-decoration: none;
    }

    .sidebar-nav {
        padding: 2rem 0;
        flex: 1 1 auto;
    }

    a.child-link {
        padding: .625rem 1.5rem;
        color: #FFF;
        display: block;
        font-size: 0.9rem;
        white-space: nowrap;
        border-left: 3px solid transparent;
        text-decoration: none;
    }

    a.sidebar-link {
        padding: .625rem 1.5rem;
        color: #FFF;
        display: block;
        font-size: 0.9rem;
        white-space: nowrap;
        border-left: 3px solid transparent;
        text-decoration: none;
    }

    .child-link i,
    .sidebar-link i,
    .dropdown-item i {
        font-size: 1.1rem;
        margin-right: .75rem;
    }


    a.sidebar-link:hover {
        background-color: rgba(255, 255, 255, .075);
        border-left: 3px solid #3b7ddd;
    }

    a.child-link:hover {
        background-color: rgba(255, 255, 255, .075);
        border-left: 3px solid #3b7ddd;
    }

    .sidebar-link.active {
        background-color: rgba(255, 255, 255, .075);
        border-left: 3px solid #3b7ddd;
    }

    .child-link.active {
        background-color: rgba(255, 255, 255, .075);
        border-left: 3px solid #3b7ddd;
    }

    .sidebar-item {
        position: relative;
    }

    .child-item {
        position: relative;
    }

    #sidebar:not(.expand) a.sidebar-link span {
        display: none;
    }

    #sidebar:not(.expand) a.child-link span {
        display: none;
    }

    #sidebar:not(.expand) .sidebar-logo a {
        display: none;
    }

    #sidebar:not(.expand) .sidebar-item .sidebar-dropdown {
        position: absolute;
        top: 0;
        left: 80px;
        /* Changed from 70px to 80px */
        background-color: #0e2238;
        padding: 0;
        min-width: 15rem;
        display: none;
    }

    #sidebar:not(.expand) .child-item .sidebar-dropdown {
        position: absolute;
        top: 0;
        left: 80px;
        /* Changed from 70px to 80px */
        background-color: #0e2238;
        padding: 0;
        min-width: 15rem;
        display: none;
    }

    #sidebar:not(.expand) .sidebar-item:hover .has-dropdown+.sidebar-dropdown {
        display: block;
        max-height: 15em;
        width: 100%;
        opacity: 1;
    }

    #sidebar:not(.expand) .child-item:hover .has-dropdown+.sidebar-dropdown {
        display: block;
        max-height: 15em;
        width: 100%;
        opacity: 1;
    }

    #sidebar.expand .sidebar-link[data-bs-toggle="collapse"]::after {
        border: solid;
        border-width: 0 .075rem .075rem 0;
        content: "";
        display: inline-block;
        padding: 2px;
        position: absolute;
        right: 1.5rem;
        top: 1.4rem;
        transform: rotate(-135deg);
        transition: all .2s ease-out;
    }

    #sidebar.expand .child-link[data-bs-toggle="collapse"]::after {
        border: solid;
        border-width: 0 .075rem .075rem 0;
        content: "";
        display: inline-block;
        padding: 2px;
        position: absolute;
        right: 1.5rem;
        top: 1.4rem;
        transform: rotate(-135deg);
        transition: all .2s ease-out;
    }

    #sidebar.expand .sidebar-link[data-bs-toggle="collapse"].collapsed::after {
        transform: rotate(45deg);
        transition: all .2s ease-out;
    }

    #sidebar.expand .child-link[data-bs-toggle="collapse"].collapsed::after {
        transform: rotate(45deg);
        transition: all .2s ease-out;
    }

    @keyframes fadeIn {
        0% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }

    .divider-custom {
        width: 100%;
        height: 2px;
        background-color: rgb(255, 128, 0);
        margin: 0;
    }


    h3,
    h2 {
        font-family: Agbalumo;
    }


    body {
        background-color: rgb(179, 185, 189);
    }

    #searchFormContainer {
        margin-top: px;
    }

    .search-input {
        flex-grow: 1;
    }

    .small-filter-btn {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    #filterContainer {
        margin-top: 10px;
        /* Adjust the top margin as needed */
    }

    /* #mealTypeFilter {
     width: 150px;
  }
  

  #mealTypeFilter.form-select {
     height: 40px;
  }
   */
    #filterContainer label {
        display: block;
        text-align: left;
        margin-bottom: 5px;
        /* Adjust the bottom margin as needed */
    }

    /* Base Styles for All Screens */
    .history-card {
        background: rgb(179, 185, 189);
        margin-bottom: 20px;
        /* Add space between cards */
    }

    .history-card .card {
        background: rgba(255, 255, 255, 0.5);
        backdrop-filter: blur(5px);
        border-radius: 15px;
        width: 100%;
        /* Ensure full width */
        height: auto;
    }

    .history-card .card-body {
        padding: 20px;
    }

    .history-card .card-title {
        font-size: 1.5rem;
        /* Adjust title font size */
    }

    .history-card .card-text {
        flex-grow: 1;
        margin-right: 0px;
    }

    .history-card .card-text p {
        margin-bottom: 5px;
        /* Add space between paragraphs */
    }

    /* Responsive Adjustments */
    @media only screen and (max-width: 768px) {
        .history-card .card-body {
            padding: 15px;
        }

        .history-card .card-title {
            font-size: 1.25rem;
            /* Slightly smaller text */
        }

        .history-card .card-text {
            font-size: 1rem;
        }

        .history-card .row {
            flex-direction: column;
            /* Stack columns vertically */
        }

        .history-card .col-8,
        .history-card .col-4 {
            width: 100%;
            text-align: center;
            margin-bottom: 10px;
        }

        .history-card .col-4 h5 {
            text-align: center;
            font-size: 1rem;
        }

        .history-card .btn {
            width: 100%;
            /* Make buttons full width */
            margin-bottom: 10px;
        }
    }

    /* Extra Small Screens */
    @media only screen and (max-width: 576px) {

        .history-card .card {
            width: 270px;
        }

        .history-card .card-body {
            padding: 10px;
        }

        .history-card .card-title {
            font-size: 1rem;
        }

        .history-card .card-text {
            font-size: 0.9rem;
        }

        .history-card .row {
            flex-direction: column;
            /* Stack columns vertically */
        }

        .history-card .col-8,
        .history-card .col-4 {
            width: 100%;
            text-align: center;
            margin-bottom: 10px;
        }

        .history-card .btn {
            width: 100%;
            /* Full-width buttons */
            margin-bottom: 10px;
        }
    }


    .dashboard {
        background: linear-gradient(135deg, rgba(0, 255, 255, 0.5), rgba(0, 123, 255, 0.5));
        backdrop-filter: blur(5px);
        border-radius: 15px;
        height: 450px;
        display: flex;
        align-items: center;
        justify-content: center;
        /* Center content horizontally */
        text-align: center;
        /* Center text */
        transition: background-color 0.3s ease;
    }

    .dashboard:hover {
        background: linear-gradient(135deg, rgba(0, 255, 255, 0.7), rgba(0, 123, 255, 0.7));
        transform: translateY(-5px);
        /* Adds a slight lift on hover */
    }

    .dashboard .card-body {
        display: flex;
        flex-direction: column;
        justify-content: center;
        /* Center content vertically */
        align-items: center;
        /* Center content horizontally */
        width: 100%;
        /* Ensure full width */
        height: 100%;
        /* Ensure full height */
        padding: 0;
        /* Remove padding */
    }

    .dashboard .card-title {
        margin-bottom: 10px;
        font-size: 1.5rem;
        font-weight: bold;
    }

    .dashboard .card-text {
        margin: 0;
        font-size: 2rem;
        font-weight: bold;
    }

    @media (max-width: 820px) {
        .dashboard {
            height: 400px;
        }


        .dashboard .card-title {
            font-size: 1.25rem;
        }

        .dashboard .card-text {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 767px) {
        .dashboard {
            height: 200px;
        }

        .dashboard .card-title {
            font-size: 1.25rem;
        }

        .dashboard .card-text {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 576px) {
        .row.g-3 {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .dashboard {
            height: 150px;
        }

        .dashboard .card-title {
            font-size: 1rem;
        }

        .dashboard .card-text {
            font-size: 1.25rem;
        }
    }


    .product-card {
        background: rgb(179, 185, 189);
    }

    .product-card .card {
        background: rgba(255, 255, 255, 0.5);
        backdrop-filter: blur(5px);
        border-radius: 15px;
        height: 500px;
    }

    .product-card .img-container {
        height: 40%;
        overflow: hidden;
        border-radius: 15px;
        border: 0.5px solid black;
    }

    .product-card .card-body {
        height: 50px;
        display: flex;
        flex-direction: column;
    }


    .product-card .card-text {
        flex-grow: 1;
        margin-right: 0px;
    }

    .product-card .card-text p {
        margin-bottom: 0px;
    }


    @media only screen and (min-width: 1401px) {
        .product-card .card-body {
            height: 275px;
            padding: 25px;
        }

        .product-card .card {
            height: 450px;
            width: 280px;
        }

        .product-card {
            padding: 8px;
        }
    }

    @media only screen and (max-width: 1400px) {
        .product-card .card-body {
            height: 275px;
            padding: 25px;
        }

        .product-card .card {
            height: 390px;
            width: 300px;
        }

    }

    @media only screen and (max-width: 1200px) {
        .product-card .card-body {
            height: 250px;
            padding: 10px;
        }

        .product-card .card {
            height: 400px;
        }
    }

    @media only screen and (max-width: 1000px) {
        .product-card .card-body {
            height: 250px;
            padding: 10px;
        }
    }

    @media only screen and (max-width: 800px) {
        .product-card .card-body {
            height: 300px;
            padding: 10px;
        }
    }

    @media only screen and (max-width: 550px) {
        .product-card .card-body {
            height: 300px;
            padding: 20px;
        }

        .product-card .card {
            width: 310px;
        }
    }

    @media only screen and (max-width: 450px) {
        .product-card .card-body {
            height: 260px;
            padding: 15px;
        }

        .product-card .card {
            height: 380px;
            width: 310px;
        }
    }

    @media only screen and (max-width: 400px) {
        .product-card .card-body {
            height: 280px;
            padding: 10px;
        }
    }

    @media only screen and (max-width: 380px) {
        .product-card .card-body {
            height: 250px;
            padding: 10px;
        }

        .product-card .card {
            width: 100%;
            /* Set width to 100% to make it full width on smaller screens */
            max-width: 320px;
            /* Adjust the max-width to make the cards smaller */
            margin: 0 auto;
            /* Center the card horizontally */
            display: flex;
            flex-direction: column;
            /* Center the content vertically */
        }
    }

    .loading-screen {
        position: fixed;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.9);
        /* Slightly transparent background */
        z-index: 9999;
        /* Ensure it's on top of all other content */
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* Spinner (You can customize the spinner as needed) */
    .spinner {
        border: 12px solid #f3f3f3;
        /* Light grey */
        border-top: 12px solid blue;
        /* Blue */
        border-radius: 50%;
        width: 80px;
        height: 80px;
        animation: spin 1.5s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .modal-content {
        background: rgba(189, 195, 199);
    }

    .loading-screen {
      position: fixed;
      width: 100%;
      height: 100%;
      background-color: rgba(255, 255, 255, 0.9);
      /* Slightly transparent background */
      z-index: 9999;
      /* Ensure it's on top of all other content */
      display: flex;
      justify-content: center;
      align-items: center;
  }

  /* Spinner (You can customize the spinner as needed) */
  .spinner {
      border: 12px solid #f3f3f3;
      /* Light grey */
      border-top: 12px solid cyan;
      /* Blue */
      border-radius: 50%;
      width: 80px;
      height: 80px;
      animation: spin 1.5s linear infinite;
  }

  @keyframes spin {
      0% {
          transform: rotate(0deg);
      }

      100% {
          transform: rotate(360deg);
      }
  }
</style>