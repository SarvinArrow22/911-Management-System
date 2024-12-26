<!-- Service Type Dropdown -->
<div class="mb-3">
                        <label for="modalServiceType">Service Type</label>
                        <select id="modalServiceType" name="service_type" class="form-control" required disabled>
                            <option value="">Select Service Type</option>
                            <?php
                            // Fetch service types from the database
                            $service_types_result = mysqli_query($conn, "SELECT * FROM service_types");
                            while ($row = mysqli_fetch_assoc($service_types_result)) {
                                echo "<option value='{$row['id']}'>{$row['service_type']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Call Type Dropdown -->
                    <div class="mb-3">
                        <label for="modalCallType">Call Type</label>
                        <select id="modalCallType" name="call_type" class="form-control" required disabled>
                            <option value="">Select Call Type</option>
                        </select>
                    </div>