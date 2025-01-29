<?php
include("././include/config.php");

if (isset($_POST['verify'])) {
    $updateStatus = $_POST['idupdate'];  // Get the ID to update
    $verifieddate = date("Y-m-d H:i:s");  // Get current timestamp
    $verifiedStatus = "Verified";  // Set the status to Verified

    // Prepare the SQL statement to update the status
    $sql = "UPDATE queuing_form SET status = ?, verifieddate = ? WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $verifiedStatus, $verifieddate, $updateStatus);

    // Execute the query
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        echo '<script>window.location.href = "supervisorAcc.php?page=supervisorqForm";</script>';
    } else {
        echo '<script>alert("Sorry, data failed to update...")</script>';
    }

    $stmt->close();
}





if(isset($_POST['search'])) {
    $selectedDate = date('m-d-Y', strtotime($_POST['selectedDate'])); // Format the selected date

    $sql = "SELECT * FROM queuing_form WHERE date = '$selectedDate' ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);
} else {
   date_default_timezone_set('Asia/Manila');

// Get the current date in the Philippines time zone
$currentDate = date('m-d-Y'); 

// Your SQL query
$sql = "SELECT * FROM queuing_form WHERE date = '$currentDate' ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
}
?>

<style>

   .radio-group
    {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
        justify-content: flex-start;
        align-items: center;
    }

    .hidden {
        display: none;
    }

    .tab {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 10px 0;
        border-radius: 5px 5px 0 0; /* Rounded top corners */
    }

    /* Style for each tab button */
    .tab button {
        background-color: #444;
        color: white;
        border: none;
        padding: 12px 20px;
        margin: 0 8px;
        cursor: pointer;
        font-size: 16px;
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    /* Hover effect for the buttons */
    .tab button:hover {
        background-color: #555;
        transform: scale(1.05); /* Slight zoom effect */
    }

    /* Active tab style */
    .tab button.active {
        background-color: #00aaff; /* Active color */
        color: white;
        font-weight: bold;
    }

    /* Focus effect for accessibility */
    .tab button:focus {
        outline: none;
    }

    /* Responsive design: stack tabs vertically on smaller screens */
    @media (max-width: 600px) {
        .tab {
            flex-direction: column; /* Stack the buttons vertically */
        }
        .tab button {
            margin: 8px 0;
        }
    }
</style>
        <!-- NAVBAR -->
        <nav style=" display: flex; align-items: center; justify-content: space-between;">

            <div style="display: flex; align-items: center; gap: 1.5rem;">
                <i class='bx bx-menu' ></i>
                <div>
                    <h5>Cargo List</h5>
                </div>
            </div>

            <div style="display: flex; margin-right: 1rem; gap: 0.5rem;">
                <div class="input-group mt-0" style="height: 2rem;">
                    <span class="input-group-text">Search</span>
                    <input type="text" class="form-control" id="searchInput" style="box-shadow: none;" placeholder="">
                </div>

                <!-- <a href="userAcc.php?page=userAddVehicleForm" class="btn btn-primary">Add Vehicle</a> -->
                <!-- <a href="adminAcc.php?page=adminAddVehicle"><button type="button" style="width:9rem;"> Add Vehicle</button></a>   -->
                <!-- <a href="supervisorAcc.php?page=supervisorAddVehicle"><button class="btn btn-primary" type="button" style="width:9rem;"> Add Vehicle</button></a> -->
            </div>

		</nav>
		<!-- NAVBAR -->

 
        
        <!-- Add this to your HTML to include the xlsx library -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

        <div class="">
            <form method="POST">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div class="input-group mt-3" style="width: 20rem; margin-left: 1.8rem;">
                        <input type="date" class="form-control" name="selectedDate" placeholder="Select Date" aria-label="Select Date" aria-describedby="searchButton">
                        <button class="btn btn-primary" type="submit" name="search" id="searchButton">Find</button>
                    </div>
                    
                    <div class="tab">
                        <button class="tablinks" onclick="openCity(event, 'Tab1')">MONTENEGRO</button>
                        <button class="tablinks" onclick="openCity(event, 'Tab2')">SANTA CLARA</button>
                        <button class="tablinks" onclick="openCity(event, 'Tab3')">FAST CAT</button>
                        <button class="tablinks" onclick="openCity(event, 'Tab4')">SUNLINE</button>
                        <button class="tablinks" onclick="openCity(event, 'Tab5')">ALD SEA TRANSPORT</button>	
                    </div>
                    <!-- <button type="button" style="width: 13rem; margin-top: 1rem;" class="btn btn-success bi bi-plus-circle"> Download as Excel</button>   -->

                </div>
                
            </form> 
        </div>
        <div class="radio-group" style="gap: 1rem; margin-left: 2rem;">
            <label>
                <input type="radio" name="range" value="5"> 1 - 5
            </label>
            <label>
                <input type="radio" name="range" value="10"> 1 - 10
            </label>
            <label>
                <input type="radio" name="range" value="all"> 1 - 25
            </label>
        </div>

        <main class="content px-3 py-2">
            <div class="container-fluid">

            <table class="table table-hover text-center" id="dataTable">
                <thead class="table-dark">
                    <tr>
                        <th style="padding: 0 4rem;" scope="col"></th>
                        <th style="padding: 0 4rem;" scope="col">No.</th>
                        <th style="padding: 0 4rem;" scope="col">Queuing No.</th>
                        <th style="padding: 0 4rem;" scope="col">Company Name / Model</th>
                        <th style="padding: 0 4rem;" scope="col">Plate Number</th>
                        <th style="padding: 0 4rem;" scope="col">Type of Vehicle</th>
                        <th style="padding: 0 4rem;" scope="col">No. of Passenger</th>
                        <th style="padding: 0 4rem;" scope="col">Destination</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $prevDate = null;
                    $counter = 1; // Start counter at 1

                    while ($row = mysqli_fetch_assoc($result)) {
                        $currentDate = $row['date']; // Get the current row's date
                        
                        // Check if it's a new day, then reset the counter
                        if ($currentDate !== $prevDate) {
                            $counter = 1; // Reset the counter at the start of each new day
                            $prevDate = $currentDate; // Update the previous date
                        }
                    ?>
                    <tr class="showModal" style="cursor: pointer;" data-row-id="<?php echo $row['id']; ?>">
                        <td>
                            <input type="checkbox" class="rowCheckbox" data-row-id="<?php echo $row['id']; ?>" />
                        </td>
                        <td><?php echo $counter; ?></td>
                        <td><?php echo $row["queuingNum"] ?></td>
                        <td><?php echo $row["companyname"] ?: $row["typeofvehicle"]; ?></td>
                        <td><?php echo $row["platenumber"] ?></td>
                        <td><?php echo $row["typeofvehicle"] ?></td>
                        <td><?php echo $row["totalpassenger"] ?></td>
                        <td><?php echo $row["destination"] ?></td>
                    </tr>
                    <?php
                        $counter++; // Increment the counter after each row
                    }
                    ?>
                </tbody>
            </table>


            <script>
                // Get all radio buttons and the table rows
                const radioButtons = document.querySelectorAll('input[name="range"]');
                const tableRows = document.querySelectorAll('#dataTable tbody tr');

                // Add event listener to each radio button
                radioButtons.forEach(button => {
                    button.addEventListener('change', function() {
                        const selectedValue = this.value;  // Get selected range value
                        
                        // Loop through each table row and hide/show based on the range
                        tableRows.forEach((row, index) => {
                            // Hide all rows initially
                            row.classList.add('hidden');

                            // Show rows based on the selected range
                            if (selectedValue === "5" && index < 5) {
                                row.classList.remove('hidden');  // Show first 5 rows
                            } else if (selectedValue === "10" && index < 10) {
                                row.classList.remove('hidden');  // Show first 10 rows
                            } else if (selectedValue === "all") {
                                row.classList.remove('hidden');  // Show all rows
                            }
                        });
                    });
                });
            </script>

            <!-- Modal for Viewing all data -->
                <div class="modal fade" id="vehicleAllInfo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div style="border: 1px solid crimson;" class="modal-dialog">
                        <div class="modal-content" style="width: 50rem;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">View all Informations</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                            <div class="modal-body" style="padding-bottom: 1.5rem;">

                                <div style="display: grid; grid-template-columns: auto auto auto auto; grid-column-gap: 0.5rem;">
                                    <div><label for="recipient-name" class="col-form-label">Date:</label><input type="text" class="form-control" id="date" readonly></div>
                                    <div><label for="recipient-time" class="col-form-label">Time:</label><input type="text" class="form-control" id="time" readonly></div>
                                    <div><label for="recipient-name" class="col-form-label">Serial No.:</label><input type="text" class="form-control" id="serial-no" readonly></div>
                                    <div><label for="recipient-name" class="col-form-label">Mobile No.:</label><input type="text" class="form-control" id="mobile-no" readonly></div>
                                </div>
            
                                <div style="display: grid; grid-template-columns: auto auto; grid-column-gap: 0.5rem;">
                                    <div><label for="recipient-name" class="col-form-label">Company Name:</label><input type="text" class="form-control" id="company-name" readonly></div>
                                    <div><label for="recipient-name" class="col-form-label">Drivers Name:</label><input type="text" class="form-control" id="drivers-name" readonly></div>
                                    <div style="display: none;"><label for="recipient-name" class="col-form-label">Status</label><input type="text" class="form-control" id="status" readonly></div>
                                </div>
                                
                                <div style="display: grid; grid-template-columns: auto auto auto auto; grid-column-gap: 0.5rem;">
                                    <div><label for="recipient-name" class="col-form-label">Plate No.:</label><input type="text" class="form-control" id="vehicle-plate-no" readonly></div>
                                    <div><label for="recipient-name" class="col-form-label">Total Passenger:</label><input type="text" class="form-control" id="total-no-passenger" readonly></div>
                                    <div><label for="recipient-name" class="col-form-label">Destination:</label><input type="text" class="form-control" id="destination" readonly></div>
                                    <div><label for="recipient-name" class="col-form-label">Gross Tonnagge:</label><input type="text" class="form-control" id="gross-tonnagge" readonly></div>
                                </div>

                                <div style="display: grid; grid-template-columns: auto auto auto auto; grid-column-gap: 0.5rem;">
                                    <div><label for="recipient-name" class="col-form-label">Type of Vehicle:</label><input type="text" class="form-control" id="typeOfVehicle" readonly></div>
                                    <div><label for="recipient-name" class="col-form-label">Model:</label><input type="text" class="form-control" id="model" readonly></div>
                                    <div><label for="recipient-name" class="col-form-label">Vehicle Category:</label><input type="text" class="form-control" id="vehicleCategory" readonly></div>
                                    <div><label for="recipient-name" class="col-form-label">Queuing No.:</label><input type="text" class="form-control" id="queuingNumber" readonly></div>
                                </div>
                                <div style="display: flex;">
                                    <!-- for Verified vehicles -->
                                    <form action="" method="post">
                                        <div>
                                            <label for="recipient-name" class="col-form-label">Verify: </label>
                                            <input type="hidden" name="idupdate" class="form-control" id="idupdate" value="SOME_ID_HERE" readonly>
                                        </div>
                                        <button type="submit" name="verify" class="btn btn-success text-black fw-bold" id="verifyButton">Verified</button>
                                    </form>
                                    

                                        

                                    <!-- for Pending vehicles -->
                                    <!-- <form action="" method="post" style="margin-left: 2rem;">
                                        <div>
                                            <label for="recipient-name" class="col-form-label">Proceed of OSS</label>
                                            <input type="hidden" name="idupdate" class="form-control" id="idupdate" value="SOME_ID_HERE" readonly>
                                        </div>
                                        <button type="submit" name="proceedofoss" class="btn btn-warning text-black fw-bold" id="proceedButton">Proceed of OSS</button>
                                    </form> -->
                                    <!-- end of Pending vehicles -->
                                </div>
                                <!-- for Verified vehicles -->
                                
                                <form action="" method="post">
                                    <div style="margin-left: 8rem; margin-top: 2rem;">
                                        <!-- <label for="recipient-name" class="col-form-label">Shiffing Line</label> -->
                                        <input type="hidden" name="idupdate" class="form-control" id="idupdate" value="SOME_ID_HERE" readonly>
                                    </div>
                                    <button class="btn btn-primary text-black fw-bold">Shiffing Line</button>
                                </form>

                                    
                                    <!-- end of Verified vehicles -->


                            </div>

                            <div class="modal-footer" style="display: flex; align-items: center;">
        
                        

                        
                                <button type="button" class="btn btn-warning text-dark fw-bold bi bi-download" disabled>Save as PDF</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>

                        </div>
                    </div>
                </div>
            <!-- Modal for Viewing all data -->

                <div id="pagination">
                    <div class="d-flex gap-2 mb-3">
                        <button id="prevPage" class="btn btn-primary">Previous</button>
                        <span id="page" class="fw-bold fs-5"></span>
                        <button id="nextPage" class="btn btn-primary">Next</button>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                        const rows = document.querySelectorAll('.showModal');

                        rows.forEach(row => {
                            row.addEventListener('click', function() {
                                // Get the specific row's ID
                                const rowId = this.dataset.rowId;

                                // Fetch the specific row's data from the server using AJAX
                                fetch('processView.php?id=' + rowId)
                                    .then(response => response.json())
                                    .then(data => {
                                        // Populate the modal fields with the fetched data
                                        document.getElementById('date').value = data.date;
                                        document.getElementById('time').value = data.time;
                                        document.getElementById('serial-no').value = data.serialNum;
                                        document.getElementById('drivers-name').value = data.driversname;
                                        document.getElementById('company-name').value = data.companyname;
                                        document.getElementById('mobile-no').value = data.mobilenumber;
                                        document.getElementById('vehicle-plate-no').value = data.platenumber;
                                        document.getElementById('total-no-passenger').value = data.totalpassenger;
                                        document.getElementById('destination').value = data.destination;
                                        document.getElementById('gross-tonnagge').value = data.grossTonnagge;
                                        document.getElementById('status').value = data.status;
                                        document.getElementById('typeOfVehicle').value = data.typeofvehicle;
                                        document.getElementById('model').value = (data.modelBrand === "") ? data.modelBrandOthers : data.modelBrand;
                                        document.getElementById('vehicleCategory').value = data.vehicleCategory;
                                        document.getElementById('queuingNumber').value = data.queuingNum;   
                                        document.getElementById('idupdate').value = data.id;

                                        // Get the buttons
                                        const verifyButton = document.getElementById('verifyButton');

                                        // Hide the buttons based on status
                                        if (data.status === 'Verified') {
                                            // If the status is 'Verified', hide the 'Verified' button
                                            verifyButton.style.display = 'none';
                                        }
                                    });

                                // Open the modal
                                const modal = new bootstrap.Modal(document.getElementById('vehicleAllInfo'));
                                modal.show();
                            });
                        });
                    });



                    //Jscript for pagination
                    $(document).ready(function() {
                        var pageSize = 25; // Number of rows per page
                        var currentPage = 1; // Current page
                        var rows = $('tbody tr'); // Rows to be paginated
                        var totalRows = rows.length;
                        var totalPages = Math.ceil(totalRows / pageSize);

                        $('#prevPage').on('click', function() {
                            if(currentPage > 1) {
                                currentPage--;
                                showPage();
                            }
                        });

                        $('#nextPage').on('click', function() {
                            if(currentPage < totalPages) {
                                currentPage++;
                                showPage();
                            }
                        });

                        function showPage() {
                            var start = (currentPage - 1) * pageSize;
                            var end = start + pageSize;
                            rows.hide().slice(start, end).show();
                            $('#page').text(currentPage + ' of ' + totalPages);
                        }

                        showPage();
                    });
                    //Jscript for pagination
                    // JavaScript for searching and filtering the table rows
                    $(document).ready(function() {
                        $('#searchInput').on('keyup', function() {
                            var searchText = $(this).val().toLowerCase(); // Get the search input and convert it to lowercase
                            var $rows = $('tbody tr'); // Select all table rows in the tbody
                            var $matchedRows = $rows.filter(function() {
                                // Check if the text of the row contains the search text
                                return $(this).text().toLowerCase().indexOf(searchText) > -1;
                            });

                            // Hide all rows initially
                            $rows.hide();

                            // If there are matched rows, show only those
                            if ($matchedRows.length > 0) {
                                $matchedRows.show();
                            } else {
                                // If no rows are matched, show a message saying "Data does not exist"
                                
                            }

                            // If search input is cleared (backspace or empty input), show all rows
                            if (searchText === '') {
                                $rows.show(); // Show all rows when the search input is empty
                            }
                        });
                    });
                    
                    //Script for converting table to excel
                    $(document).ready(function() {
                    // Add an event listener to the button using jQuery
                    $("button.bi-plus-circle").click(function() {
                        const table = $("table"); // Select the table using jQuery
                
                        // Create a new workbook and worksheet
                        const wb = XLSX.utils.book_new();
                        const ws_data = [];
                
                        // Get header row, excluding the "dontInclude" column
                        const header = [];
                        table.find("thead th:not(#dontInclude)").each(function() {
                            header.push($(this).text().trim());
                        });
                        ws_data.push(header);
                
                        // Get data rows
                        table.find("tbody tr").each(function() {
                            const row = [];
                            $(this).find("td:not(:first-child)").each(function() {
                                row.push($(this).text().trim());
                            });
                            ws_data.push(row);
                        });
                
                        // Create a worksheet from the table data
                        const ws = XLSX.utils.aoa_to_sheet(ws_data);
                
                        // Add the worksheet to the workbook
                        XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
                
                        // Get the first row's date to use as the filename
                        const firstRowDate = table.find("tbody tr:first-child td:nth-child(4)").text(); // 4th column (index 3)
                
                        // Download Excel file
                        XLSX.writeFile(wb, `${firstRowDate}_table_data.xlsx`);
                    });
                });
                //Script for converting table to excel   
            </script>

            

        </main>