<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal</title>
    <link rel="icon" href="img/portal2.png" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/css/sanden/portal.css') }}">
  

</head>
<body>
    <div class="header">
        <!--Content before waves-->
        <div class="inner-header flex">
            <!--Just the logo.. Don't mind this-->
            <svg version="1.1" class="logo" baseProfile="tiny" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 500 500" xml:space="preserve">
                <path fill="#FFFFFF" stroke="#000000" stroke-width="10" stroke-miterlimit="10" d="M57,283" />
            </svg>
            <br>
            <div>
                <img src="img/Sanden_Logo_SCP2_.png" alt="Logo Image" style="width: 400px; padding-top: 50px;">
  <p class="company-info">
        105 Makiling Drive, Carmelray Industrial Park II <br>
        Km 54 National Hi-way, Brgy. Milagrosa <br>
        Calamba City, 4027 Laguna <br>
        Tel. No.: (049) 554-9970 up to 79 <br>
        Manila Line: (02) 8-898-5110
    </p>            </div>
        </div>
        <!--Waves Container-->
    
        <!--Header ends-->
    </div>

    <!--Content starts-->
    <div class="snowflakes" aria-hidden="true">
  <div class="snowflake">
  ❅
  </div>
  <div class="snowflake">
  ❅
  </div>
  <div class="snowflake">
  ❆
  </div>
  <div class="snowflake">
  ❄
  </div>
  <div class="snowflake">
  ❅
  </div>
  <div class="snowflake">
  ❆
  </div>
  <div class="snowflake">
  ❄
  </div>
  <div class="snowflake">
  ❅
  </div>
  <div class="snowflake">
  ❆
  </div>
  <div class="snowflake">
  ❄
  </div>
</div>
    <div class="container" id="content">
        <!-- Content will be dynamically loaded here -->
    </div>

    <!-- Pagination links -->
    <div class="pagination" id="pagination">
        <!-- Pagination links will be generated here -->
    </div>


</body>
  <footer>
      
            <p>&copy; Sanden Cold Chain System Philippines Inc. 2025</p>
            <p>Management Information System</p>
    
    </footer>
</html>
  

   <script>
    // Sample data for demonstration
   const data = [
    @if(Auth::user()->role === 'users_s2')
    { href: "/subsystem/edms", imgSrc: "{{ asset('img/edms.png') }}", alt: "Electronic Document Management System", title: "EDMS" },
    @endif

    @if(Auth::user()->role === 'developer')
    { href: "/subsystem/edms", imgSrc: "{{ asset('img/edms.png') }}", alt: "Electronic Document Management System", title: "EDMS" },
    { href: "/subsystem/forms", imgSrc: "{{ asset('img/images.png') }}", alt: "Forms", title: "Forms" },
    { href: "/subsystem/authorization", imgSrc: "{{ asset('img/itrequest.png') }}", alt: "Authorization", title: "Authorization" },
     { href: "/subsystem/wellness", imgSrc: "{{ asset('img/itrequest.png') }}", alt: "Wellness", title: "Wellness" },
          { href: "/dropball", imgSrc: "{{ asset('img/53283.png') }}", alt: "Drop Ball Raflle", title: "Drop Ball Raffle Game" },
    @endif
];

    // Decide items per page depending on screen width
function getItemsPerPage() {
    if (window.innerWidth <= 480) {
        return 6;   // 2 rows × 3 per row
    } else if (window.innerWidth <= 768) {
        return 9;   // maybe 3 rows × 3 on tablets
    } else {
        return 14;  // desktop
    }
}

    let itemsPerPage = getItemsPerPage();
    let currentPage = 1;

    function displayItems(page) {
        const contentContainer = document.getElementById('content');
        contentContainer.innerHTML = '';

        const startIndex = (page - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const itemsToShow = data.slice(startIndex, endIndex);

        itemsToShow.forEach(item => {
            const box = document.createElement('div');
            box.classList.add('box');

            const link = document.createElement('a');
            link.href = item.href;
            link.target = "_blank"; 

            const img = document.createElement('img');
            img.src = item.imgSrc;
            img.alt = item.alt;

            const span = document.createElement('span');
            span.textContent = item.title;

            link.appendChild(img);
            link.appendChild(span);
            box.appendChild(link);
            contentContainer.appendChild(box);
        });
    }

    function generatePaginationLinks() {
        const totalPages = Math.ceil(data.length / itemsPerPage);
        const paginationContainer = document.getElementById('pagination');
        paginationContainer.innerHTML = '';

        for (let i = 1; i <= totalPages; i++) {
            const link = document.createElement('a');
            link.href = '#';
            link.textContent = i;
            if (i === currentPage) {
                link.classList.add('active');
            }
            link.onclick = function(e) {
                e.preventDefault();
                currentPage = i;
                displayItems(currentPage);
                highlightActiveLink();
            };
            paginationContainer.appendChild(link);
        }
    }

    function highlightActiveLink() {
        const paginationLinks = document.querySelectorAll('.pagination a');
        paginationLinks.forEach(link => {
            link.classList.remove('active');
            if (parseInt(link.textContent) === currentPage) {
                link.classList.add('active');
            }
        });
    }

    // Handle window resize (recalculate items per page)
    window.addEventListener("resize", () => {
        itemsPerPage = getItemsPerPage();
        currentPage = 1;
        displayItems(currentPage);
        generatePaginationLinks();
    });

    // Initial setup
    displayItems(currentPage);
    generatePaginationLinks();
</script>
