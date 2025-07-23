document.addEventListener("livewire:navigated", () => {
    // Toggle sidebar function
    const toggleSidebarBtn = document.getElementById("toggleSidebar");
    const sidebar = document.getElementById("sidebar");
    const contentWrapper = document.getElementById("contentWrapper");

    toggleSidebarBtn.addEventListener("click", function () {
        sidebar.classList.toggle("collapsed");
        contentWrapper.classList.toggle("sidebar-collapsed");
    });

    // Add active class to clicked sidebar item
    const sidebarItems = document.querySelectorAll(".sidebar-item");
    sidebarItems.forEach((item) => {
        item.addEventListener("click", function () {
            // Remove active class from all sidebar items
            sidebarItems.forEach((link) => link.classList.remove("active"));
            // Add active class to the clicked item
            this.classList.add("active");
        });
    });

    // FOR CHART - Only initialize if chart element exists
    const ctx = document.getElementById("myChart");
    
    if (ctx) {
        new Chart(ctx, {
            type: "line",
            data: {
                labels: [
                    "January",
                    "February",
                    "March",
                    "April",
                    "May",
                    "June",
                    "July",
                    "August",
                    "September",
                    "October",
                    "November",
                    "December",
                ],
                datasets: [
                    {
                        label: "Expense: 20.1K",
                        data: [12, 13, 15, 15, 12, 13, 18, 14, 16, 17, 19, 20],
                        borderColor: "rgba(255, 99, 132, 1)", // red line
                        borderWidth: 2,
                        fill: false,
                    },
                    {
                        label: "Revenue: 25.5K",
                        data: [14, 15, 18, 16, 14, 16, 20, 18, 19, 21, 23, 24],
                        borderColor: "rgba(54, 162, 235, 1)", // blue line
                        borderWidth: 2,
                        fill: false,
                    },
                ],
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                },
            },
        });
    }
});
