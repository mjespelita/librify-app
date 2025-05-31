$(document).ready(function() {

    // Get the current URL path
    let path = window.location.pathname;

    // Get the last segment (user ID)
    let lastId = path.split('/').filter(Boolean).pop();

    // GLOBAL CHART INSTANCE
    let lineChartEmployeeDetails = null;

    // Helper: Populate year dropdown from 2000 to current
    const yearSelect = $('#yearSelect');
    const now = new Date();
    const currentYear = now.getFullYear();
    const currentMonth = now.getMonth() + 1; // 1-based

    for (let year = 2000; year <= currentYear; year++) {
        yearSelect.append(`<option value="${year}" ${year === currentYear ? 'selected' : ''}>${year}</option>`);
    }

    $('#monthSelect').val(currentMonth); // Set current month

    // ✅ Initial Chart Load
    $.get('/get-employee-task-count/' + lastId, function(res) {
        const thisDay = new Date();
        const currentYear = thisDay.getFullYear();
        const currentMonth = thisDay.getMonth() + 1;

        const lastDayOfMonth = new Date(currentYear, currentMonth, 0).getDate();
        const monthName = new Intl.DateTimeFormat('en-US', {
            month: 'long'
        }).format(thisDay);

        $('#selected-month-year').text(`${monthName} ${currentYear}`);

        const categories = [];
        const pendingData = [];
        const completedData = [];

        const taskDataMap = {};
        res.forEach(item => taskDataMap[item.date] = item);

        for (let day = 1; day <= lastDayOfMonth; day++) {
            const dateStr = `${currentYear}-${String(currentMonth).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            categories.push(`${monthName} ${day}`);
            pendingData.push(taskDataMap[dateStr]?.pending || 0);
            completedData.push(taskDataMap[dateStr]?.completed || 0);
        }

        const chartOptions = {
            chart: {
                type: 'line',
                height: 300
            },
            stroke: {
                curve: 'smooth'
            },
            colors: ["#FF1654", "#198754"],
            series: [{
                    name: 'Pending',
                    data: pendingData
                },
                {
                    name: 'Completed',
                    data: completedData
                }
            ],
            xaxis: {
                categories: categories
            }
        };

        lineChartEmployeeDetails = new ApexCharts(
            document.querySelector("#lineChartEmployeeDetailsInitialLoad"),
            chartOptions
        );
        lineChartEmployeeDetails.render();
    });

    // ✅ Dynamic Chart Load (month & year)
    function loadEmployeeTaskChart(month, year, userId) {
        $.get(`/get-employee-task-count-by-month/${userId}/${month}/${year}`, function(res) {
            const currentDate = new Date(year, month - 1);
            const lastDay = new Date(year, month, 0).getDate();
            const monthName = new Intl.DateTimeFormat('en-US', {
                month: 'long'
            }).format(currentDate);

            $('#selected-month-year').text(`${monthName} ${year}`);

            const categories = [];
            const pendingData = [];
            const completedData = [];

            const taskDataMap = {};
            res.forEach(item => taskDataMap[item.date] = item);

            for (let day = 1; day <= lastDay; day++) {
                const dateStr = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                categories.push(`${monthName} ${day}`);
                pendingData.push(taskDataMap[dateStr]?.pending || 0);
                completedData.push(taskDataMap[dateStr]?.completed || 0);
            }

            const chartOptions = {
                chart: {
                    type: 'line',
                    height: 300
                },
                stroke: {
                    curve: 'smooth'
                },
                colors: ["#FF1654", "#198754"],
                series: [{
                        name: 'Pending',
                        data: pendingData
                    },
                    {
                        name: 'Completed',
                        data: completedData
                    }
                ],
                xaxis: {
                    categories: categories
                }
            };

            if (lineChartEmployeeDetails instanceof ApexCharts) {
                lineChartEmployeeDetails.destroy();
            }

            lineChartEmployeeDetails = new ApexCharts(
                document.querySelector("#lineChartEmployeeDetailsFilteredMonthYear"),
                chartOptions
            );
            lineChartEmployeeDetails.render();
        });
    }

    // ✅ Load data on button click
    $('#loadDataBtn').on('click', function() {
        const selectedMonth = parseInt($('#monthSelect').val());
        const selectedYear = parseInt($('#yearSelect').val());

        $('#lineChartEmployeeDetailsInitialLoad').hide();
        $('#lineChartEmployeeDetailsFilteredMonthYear').show();

        loadEmployeeTaskChart(selectedMonth, selectedYear, lastId);
    });

    // ✅ Ensure only initial chart is shown at start
    $('#lineChartEmployeeDetailsFilteredMonthYear').hide();

    // end -----------------------

    $(".search-my-tasks").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $(".task-card").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    $.get('/get-item-data-for-graph', function(res) {
        var pieChartItemsOverviewOptions = {
            chart: {
                type: 'pie', // Set the chart type to pie
            },
            colors: ["#247BA0", "#FFD700", "#FF1654"], // Customize the colors
            series: [
                parseInt(res.inWarehousesCount),
                parseInt(res.onSitesCount),
                parseInt(res.damagesCount)
            ], // Pie chart data (as an array of numbers)
            labels: ['In Warehouse', 'On Site', 'Damages'], // Labels for the slices
            title: {
                text: 'Items',
                align: 'center'
            },
            tooltip: {
                y: {
                    formatter: function(value) {
                        return value + ' items'; // Customize the tooltip to show the value with "units"
                    }
                }
            }
        };

        console.log(res);

        // Create the pie chart
        var pieChartItems = new ApexCharts(document.querySelector("#pie-chart-item-overview"), pieChartItemsOverviewOptions);
        pieChartItems.render();
    })

    // Bar chart onsite

    $.get('/get-item-data-for-stats', function(res) {

        let onsiteItems = [];
        let onsiteCounts = [];

        let damagedItems = [];
        let damagedCounts = [];

        let onsiteAggregated = {}; // Object to store aggregated onsite items
        let damagedAggregated = {}; // Object to store aggregated damaged items

        // Ensure res.final exists and is an array
        if (Array.isArray(res.final)) {
            res.final.forEach(element => {
                // Loop through onsite items if they exist
                if (Array.isArray(element.onsites)) {
                    element.onsites.forEach(onsite => {
                        if (onsiteAggregated[onsite.name]) {
                            onsiteAggregated[onsite.name] += onsite.count; // Add count if item already exists
                        } else {
                            onsiteAggregated[onsite.name] = onsite.count; // Otherwise, initialize it
                        }
                    });
                }

                // Loop through damage items if they exist
                if (Array.isArray(element.damages)) {
                    element.damages.forEach(damage => {
                        if (damagedAggregated[damage.name]) {
                            damagedAggregated[damage.name] += damage.count; // Add count if item already exists
                        } else {
                            damagedAggregated[damage.name] = damage.count; // Otherwise, initialize it
                        }
                    });
                }
            });
        }

        // Now convert the aggregated data into arrays for the chart
        onsiteItems = Object.keys(onsiteAggregated);
        onsiteCounts = Object.values(onsiteAggregated);

        damagedItems = Object.keys(damagedAggregated);
        damagedCounts = Object.values(damagedAggregated);

        // Onsite Bar Chart
        var barChartOnsiteOptions = {
            chart: {
                type: 'bar',
                height: 300
            },
            colors: ['#35A9DA'],
            series: [{
                name: 'sales',
                data: onsiteCounts
            }],
            xaxis: {
                categories: onsiteItems
            }
        }

        var barChartOnsite = new ApexCharts(document.querySelector("#barChartOnsite"), barChartOnsiteOptions);
        barChartOnsite.render();

        // Damages Bar Chart
        var barChartDamagesOptions = {
            chart: {
                type: 'bar',
                height: 300
            },
            colors: ['#FF1654'],
            series: [{
                name: 'sales',
                data: damagedCounts
            }],
            xaxis: {
                categories: damagedItems
            }
        }

        var barChartDamages = new ApexCharts(document.querySelector("#barChartDamages"), barChartDamagesOptions);
        barChartDamages.render();
    });


    const searchBox = document.getElementById('searchBox');
    const mapIframe = document.getElementById('googleMap');

    // Function to update the iframe src with the searched place
    function updateMapSearch(query) {
        const baseURL = "https://www.google.com/maps/embed/v1/place?q=";
        const apiKey = "AIzaSyBJyFU3OF64Fn1tPHkP37DifH4V0uhuU8w"; // Replace with your Google Maps API Key
        const newURL = `${baseURL}${encodeURIComponent(query)}&key=${apiKey}`;
        mapIframe.src = newURL;

        // Add To Inout

        $('.mapCoordinatesData').val(newURL);
    }

    $('.searchBoxInput').on('keyup', function() {
        const query = searchBox.value.trim();
        if (query) {
            updateMapSearch(query);
        }
    })

    // serial numbers

    $.get('/get-item-serial-numbers', function(res) {

        // Dummy data for serial numbers (you can fetch this data from a server if needed)
        var serialNumbers = res.serial_numbers;

        // Function to update the quantity input field
        function updateQuantity() {
            var quantity = $('#tagContainer .badge').length; // Get the number of tags
            $('#quantity').val(quantity); // Set the quantity input to the number of tags
        }

        // Function to update the hidden input field with the tags
        function updateSerialNumbers() {
            var tags = [];
            $('#tagContainer .badge').each(function() {
                tags.push($(this).text());
            });
            $('#serial_numbers').val(tags.join(', '));
        }

        // Function to check if the serial number is already added
        function isSerialNumberDuplicate(serialNumber) {
            var existingSerials = [];
            $('#tagContainer .badge').each(function() {
                existingSerials.push($(this).text());
            });
            return existingSerials.includes(serialNumber);
        }

        $('#tagInput').on('input', function() {
            var searchTerm = $(this).val().toLowerCase();
            var filteredSerials = serialNumbers.filter(function(serial) {
                return serial.toLowerCase().includes(searchTerm);
            });

            $('#serialDropdown').empty();

            if (filteredSerials.length > 0) {
                filteredSerials.forEach(function(serial) {
                    $('#serialDropdown').append('<li><a class="dropdown-item" href="#">' + serial + '</a></li>');
                });
                $('#serialDropdown').show();
            } else {
                $('#serialDropdown').hide(); // Hide if no matches
            }
        });

        // Handle serial number selection from the dropdown
        $(document).on('click', '#serialDropdown .dropdown-item', function() {
            var serialNumber = $(this).text();

            // Check if the serial number is already added
            if (isSerialNumberDuplicate(serialNumber)) {
                alert("Serial number already chosen.");
                return;
            }

            // Add the serial number as a tag
            var tagElement = $('<span class="badge bg-success me-2 mb-2" style="cursor: pointer;"></span>').text(serialNumber);

            $('#tagContainer').append(tagElement);
            updateSerialNumbers();
            updateQuantity();
            $('#tagInput').val('');
            $('#serialDropdown').hide();
        });

        // Add serial number manually when clicking 'Add Serial Number'
        $('#addTagBtn').click(function() {
            var tag = $('#tagInput').val().trim();

            // Check if the input field is not empty and the serial number isn't already chosen
            if (tag !== "") {
                if (isSerialNumberDuplicate(tag)) {
                    alert("Serial number already chosen.");
                    return;
                }

                // Create a new tag element
                var tagElement = $('<span class="badge bg-success me-2 mb-2" style="cursor: pointer;"></span>').text(tag);

                // Append the tag to the tag container
                $('#tagContainer').append(tagElement);
                updateSerialNumbers();
                updateQuantity();
                $('#tagInput').val('');
            }
        });

        // Optionally: Allow clicking on a tag to remove it
        $(document).on('click', '.badge', function() {
            $(this).remove(); // Remove the clicked tag
            updateSerialNumbers();
            updateQuantity();
        });

    })

    // Toggle serial number ===================================================================================

    // Initially disable the quantity field if serial numbers are provided
    toggleQuantityField();

    // Toggle between "Has Serial Number" and "No Serial Number"
    $('#serialNumberToggle').change(function() {
        toggleQuantityField();
    });

    $('#serialNumberToggle').is(':checked')

    function toggleQuantityField() {
        var hasSerialNumbers = $('#serialNumberToggle').is(':checked'); // Check if the toggle is on

        if (hasSerialNumbers) {
            // If serial numbers are present, make quantity input readonly
            $('#quantity').prop('readonly', true);
            $('#serialNumberLabel').text('Has Serial Number');
            $('.serialNumberCustomToggle').slideDown();
        } else {
            // If no serial numbers, allow input in quantity
            $('#quantity').prop('readonly', false);
            $('#serialNumberLabel').text('No Serial Number');
            $('.serialNumberCustomToggle').slideUp();
        }
    }

    // search serial numbers    
    $(".searchedSerialNumberInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $(".searchedSerialNumberResult").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    let currentDate = new Date();
    let selectedDate = null;

    // ✅ Fixed: Ensure the correct local date (prevents time zone shift issues)
    let today = new Date().toLocaleDateString('en-CA'); // Format: YYYY-MM-DD

    function renderCalendar() {
        const monthYear = document.getElementById("month-year");
        const daysContainer = document.getElementById("calendar-days");
        daysContainer.innerHTML = "";

        let year = currentDate.getFullYear();
        let month = currentDate.getMonth();

        monthYear.textContent = new Intl.DateTimeFormat("en-US", {
            month: "long",
            year: "numeric"
        }).format(currentDate);

        let firstDay = new Date(year, month, 1).getDay();
        let lastDate = new Date(year, month + 1, 0).getDate();

        // Add empty divs for alignment (days before first day of the month)
        for (let i = 0; i < firstDay; i++) {
            let emptyDiv = document.createElement("div");
            daysContainer.appendChild(emptyDiv);
        }

        // Generate day elements
        for (let day = 1; day <= lastDate; day++) {
            let dayDiv = document.createElement("div");
            dayDiv.textContent = day;
            dayDiv.classList.add("day");
            let dateStr = new Date(year, month, day).toLocaleDateString('en-CA');

            dayDiv.dataset.date = dateStr;
            if (dateStr === today) {
                dayDiv.classList.add("today");
            }

            dayDiv.addEventListener("click", function() {
                if (selectedDate) {
                    selectedDate.classList.remove("selected");
                }
                selectedDate = this;
                this.classList.add("selected");
                updateDeadlineInput();
            });

            daysContainer.appendChild(dayDiv);
        }
    }

    function changeMonth(offset) {
        currentDate.setMonth(currentDate.getMonth() + offset);
        renderCalendar();
    }

    // Event listeners for previous/next month
    document.querySelector(".prevMonth").addEventListener("click", () => changeMonth(-1));
    document.querySelector(".nextMonth").addEventListener("click", () => changeMonth(1));

    function populateTimeSelectors() {
        const hourPicker = document.getElementById("hourPicker");
        const minutePicker = document.getElementById("minutePicker");

        // Populate hour selector (1-12)
        for (let i = 1; i <= 12; i++) {
            let option = document.createElement("option");
            option.value = i.toString().padStart(2, '0');
            option.textContent = i;
            hourPicker.appendChild(option);
        }

        // Populate minute selector (00, 05, 10... 55)
        for (let i = 0; i < 60; i += 5) {
            let option = document.createElement("option");
            option.value = i.toString().padStart(2, '0');
            option.textContent = i.toString().padStart(2, '0');
            minutePicker.appendChild(option);
        }
    }

    function updateDeadlineInput() {
        if (selectedDate) {
            let selectedHour = document.getElementById("hourPicker").value;
            let selectedMinute = document.getElementById("minutePicker").value;
            let ampm = document.getElementById("ampmPicker").value;

            let hour = parseInt(selectedHour, 10);
            if (ampm === "PM" && hour < 12) hour += 12;
            if (ampm === "AM" && hour === 12) hour = 0;

            document.getElementById("deadline").value =
                `${selectedDate.dataset.date}T${hour.toString().padStart(2, '0')}:${selectedMinute}`;
        }
    }

    // Attach event listeners to update deadline input on change
    document.getElementById("hourPicker").addEventListener("change", updateDeadlineInput);
    document.getElementById("minutePicker").addEventListener("change", updateDeadlineInput);
    document.getElementById("ampmPicker").addEventListener("change", updateDeadlineInput);

    // Initialize
    populateTimeSelectors();
    renderCalendar();
})