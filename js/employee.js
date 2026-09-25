document.addEventListener("DOMContentLoaded", function () {

    const stateDropdown =
        document.getElementById("state");

    const districtDropdown =
        document.getElementById("district");

    const cityDropdown =
        document.getElementById("city");


    // =====================================
    // LOAD STATES
    // =====================================

    fetch("api/get_states.php")

        .then(response => {

            if (!response.ok) {
                throw new Error("Failed to load states");
            }

            return response.json();

        })

        .then(states => {

            states.forEach(state => {

                const option =
                    document.createElement("option");

                // Store database ID
                option.value = state.id;

                // Display state name
                option.textContent =
                    state.state_name;

                stateDropdown.appendChild(option);

            });

        })

        .catch(error => {

            console.error(
                "State Error:",
                error
            );

        });


    // =====================================
    // STATE → DISTRICT
    // =====================================

    stateDropdown.addEventListener(
        "change",
        function () {

            const stateId = this.value;


            // Clear district
            districtDropdown.innerHTML =
                '<option value="">Select District</option>';


            // Clear city
            cityDropdown.innerHTML =
                '<option value="">Select City</option>';


            // Disable both
            districtDropdown.disabled = true;
            cityDropdown.disabled = true;


            // Nothing selected
            if (stateId === "") {
                return;
            }


            // =================================
            // GET DISTRICTS
            // =================================

            fetch(
                "api/get_districts.php?state_id=" +
                encodeURIComponent(stateId)
            )

                .then(response => {

                    if (!response.ok) {
                        throw new Error(
                            "Failed to load districts"
                        );
                    }

                    return response.json();

                })

                .then(districts => {

                    districts.forEach(district => {

                        const option =
                            document.createElement("option");

                        // Store district ID
                        option.value =
                            district.id;

                        // Display district name
                        option.textContent =
                            district.district_name;

                        districtDropdown.appendChild(
                            option
                        );

                    });


                    // Enable district
                    districtDropdown.disabled = false;

                })

                .catch(error => {

                    console.error(
                        "District Error:",
                        error
                    );

                });

        }
    );


    // =====================================
    // DISTRICT → CITY
    // =====================================

    districtDropdown.addEventListener(
        "change",
        function () {

            const districtId = this.value;

            // Clear city
            cityDropdown.innerHTML =
                '<option value="">Select City</option>';


            // Disable city
            cityDropdown.disabled = true;


            // Nothing selected
            if (districtId === "") {
                return;
            }


            // =================================
            // GET CITIES
            // =================================

            fetch(
                "api/get_cities.php?district_id=" +
                encodeURIComponent(districtId)
            )

                .then(response => {

                    if (!response.ok) {
                        throw new Error(
                            "Failed to load cities"
                        );
                    }

                    return response.json();

                })

                .then(cities => {

                    cities.forEach(city => {

                        const option =
                            document.createElement("option");

                        // Store city ID
                        option.value =
                            city.id;

                        // Display city name
                        option.textContent =
                            city.city_name;

                        cityDropdown.appendChild(option);

                    });


                    // Enable city
                    cityDropdown.disabled = false;

                }
            
            
            )

                .catch(error => {

                    console.error(
                        "City Error:",
                        error
                    );

                });

        }
    );

});