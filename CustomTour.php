<?php
// Start the session
session_start();

// Set page title
$pageTitle = "Home";
?>
<!DOCTYPE html>

<html lang="en">
<head>
    <?php include 'head.php'; ?>
</head>
<body>
<?php include 'header.php'; ?>

<link rel="stylesheet" type="text/css" href="css/CustomTour.css">
<!-- Hero Section -->
<section class="tours-hero custom-tour-hero">
  <div class="container">
    <h1 data-aos="fade-down">Build Your Dream Tour</h1>
    <p data-aos="fade-up">Create a personalized travel experience by selecting your destination, flights, accommodations, and activities</p>
  </div>
</section>

<!-- Main Build Tour Section -->
<section class="build-tour-section">
  <div class="container">
    <!-- Progress Tracker -->
    <div class="progress-tracker" data-aos="fade-up">
      <div class="progress-bar">
        <div class="progress-fill" id="progressFill"></div>
      </div>
      <div class="progress-steps">
        <div class="step active" data-step="destination">
          <div class="step-icon"><i class="fas fa-map-marker-alt"></i></div>
          <div class="step-text">Destination</div>
        </div>
        <div class="step" data-step="dates">
          <div class="step-icon"><i class="fas fa-calendar-alt"></i></div>
          <div class="step-text">Dates</div>
        </div>
        <div class="step" data-step="flights">
          <div class="step-icon"><i class="fas fa-plane"></i></div>
          <div class="step-text">Flights</div>
        </div>
        <div class="step" data-step="hotels">
          <div class="step-icon"><i class="fas fa-hotel"></i></div>
          <div class="step-text">Hotels</div>
        </div>
        <div class="step" data-step="activities">
          <div class="step-icon"><i class="fas fa-hiking"></i></div>
          <div class="step-text">Activities</div>
        </div>
        <div class="step" data-step="review">
          <div class="step-icon"><i class="fas fa-check-circle"></i></div>
          <div class="step-text">Review</div>
        </div>
      </div>
    </div>

    <!-- Tour Builder Container -->
    <div class="tour-builder" data-aos="fade-up">
      <!-- Step 1: Destination -->
      <div class="build-step active" id="destinationStep">
        <div class="step-header">
          <h2>Choose Your Destination</h2>
          <p>Select where you want to go for your dream vacation</p>
        </div>

        <div class="filter-container">
          <div class="filter-options">
            <div class="filter-group">
              <label>Continent</label>
              <select id="continentSelect">
                <option value="">All Continents</option>
                <option value="europe">Europe</option>
                <option value="asia">Asia</option>
                <option value="africa">Africa</option>
                <option value="north-america">North America</option>
                <option value="south-america">South America</option>
                <option value="australia">Australia & Oceania</option>
              </select>
            </div>

            <div class="filter-group">
              <label>Country</label>
              <select id="countrySelect">
                <option value="">Select Continent First</option>
              </select>
            </div>

            <div class="filter-group search-group">
              <label>Search Destinations</label>
              <input type="text" id="destinationSearch" placeholder="Search cities or places...">
            </div>
          </div>
        </div>

        <div class="destination-grid">
          <!-- Destinations will be dynamically populated based on filters -->
          <div class="destination-card" data-destination="paris" data-country="france" data-continent="europe">
            <div class="destination-image">
              <img src="images/paris.webp" alt="Paris">
            </div>
            <div class="destination-info">
              <h3>Paris</h3>
              <p><i class="fas fa-map-marker-alt"></i> France, Europe</p>
              <div class="destination-highlights">
                <span><i class="fas fa-star"></i> 4.8</span>
                <span><i class="fas fa-eye"></i> Cultural</span>
                <span><i class="fas fa-utensils"></i> Culinary</span>
              </div>
            </div>
          </div>

          <div class="destination-card" data-destination="rome" data-country="italy" data-continent="europe">
            <div class="destination-image">
              <img src="https://images.pexels.com/photos/532263/pexels-photo-532263.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Rome">
            </div>
            <div class="destination-info">
              <h3>Rome</h3>
              <p><i class="fas fa-map-marker-alt"></i> Italy, Europe</p>
              <div class="destination-highlights">
                <span><i class="fas fa-star"></i> 4.7</span>
                <span><i class="fas fa-landmark"></i> Historical</span>
                <span><i class="fas fa-utensils"></i> Culinary</span>
              </div>
            </div>
          </div>

          <div class="destination-card" data-destination="tokyo" data-country="japan" data-continent="asia">
            <div class="destination-image">
              <img src="https://images.pexels.com/photos/2506923/pexels-photo-2506923.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Tokyo">
            </div>
            <div class="destination-info">
              <h3>Tokyo</h3>
              <p><i class="fas fa-map-marker-alt"></i> Japan, Asia</p>
              <div class="destination-highlights">
                <span><i class="fas fa-star"></i> 4.9</span>
                <span><i class="fas fa-city"></i> Urban</span>
                <span><i class="fas fa-utensils"></i> Culinary</span>
              </div>
            </div>
          </div>

          <div class="destination-card" data-destination="bali" data-country="indonesia" data-continent="asia">
            <div class="destination-image">
              <img src="https://images.pexels.com/photos/1694621/pexels-photo-1694621.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Bali">
            </div>
            <div class="destination-info">
              <h3>Bali</h3>
              <p><i class="fas fa-map-marker-alt"></i> Indonesia, Asia</p>
              <div class="destination-highlights">
                <span><i class="fas fa-star"></i> 4.8</span>
                <span><i class="fas fa-water"></i> Beach</span>
                <span><i class="fas fa-spa"></i> Relaxation</span>
              </div>
            </div>
          </div>

          <div class="destination-card" data-destination="cape-town" data-country="south-africa" data-continent="africa">
            <div class="destination-image">
              <img src="https://images.pexels.com/photos/259447/pexels-photo-259447.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Cape Town">
            </div>
            <div class="destination-info">
              <h3>Cape Town</h3>
              <p><i class="fas fa-map-marker-alt"></i> South Africa, Africa</p>
              <div class="destination-highlights">
                <span><i class="fas fa-star"></i> 4.7</span>
                <span><i class="fas fa-mountain"></i> Scenic</span>
                <span><i class="fas fa-paw"></i> Wildlife</span>
              </div>
            </div>
          </div>

          <div class="destination-card" data-destination="new-york" data-country="usa" data-continent="north-america">
            <div class="destination-image">
              <img src="https://images.pexels.com/photos/466685/pexels-photo-466685.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="New York">
            </div>
            <div class="destination-info">
              <h3>New York</h3>
              <p><i class="fas fa-map-marker-alt"></i> USA, North America</p>
              <div class="destination-highlights">
                <span><i class="fas fa-star"></i> 4.6</span>
                <span><i class="fas fa-city"></i> Urban</span>
                <span><i class="fas fa-shopping-bag"></i> Shopping</span>
              </div>
            </div>
          </div>
        </div>

        <div class="step-navigation">
          <button class="next-btn" id="destinationNextBtn" disabled>Continue to Dates <i class="fas fa-arrow-right"></i></button>
        </div>
      </div>

      <!-- Step 2: Dates -->
      <div class="build-step" id="datesStep">
        <div class="step-header">
          <h2>Select Your Travel Dates</h2>
          <p>Choose when you want to travel</p>
        </div>

        <div class="dates-container">
          <div class="date-picker">
            <div class="date-group">
              <label>Departure Date</label>
              <input type="date" id="departureDate" min="">
            </div>
            <div class="date-group">
              <label>Return Date</label>
              <input type="date" id="returnDate" min="">
            </div>
          </div>

          <div class="travelers-select">
            <label>Number of Travelers</label>
            <div class="traveler-counter">
              <div class="traveler-type">
                <span>Adults</span>
                <div class="counter">
                  <button class="decrease-btn"><i class="fas fa-minus"></i></button>
                  <input type="number" value="2" min="1" max="10" id="adultCount">
                  <button class="increase-btn"><i class="fas fa-plus"></i></button>
                </div>
              </div>
              <div class="traveler-type">
                <span>Children (2-17)</span>
                <div class="counter">
                  <button class="decrease-btn"><i class="fas fa-minus"></i></button>
                  <input type="number" value="0" min="0" max="10" id="childCount">
                  <button class="increase-btn"><i class="fas fa-plus"></i></button>
                </div>
              </div>
              <div class="traveler-type">
                <span>Infants (0-2)</span>
                <div class="counter">
                  <button class="decrease-btn"><i class="fas fa-minus"></i></button>
                  <input type="number" value="0" min="0" max="5" id="infantCount">
                  <button class="increase-btn"><i class="fas fa-plus"></i></button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="step-navigation">
          <button class="back-btn" id="datesBackBtn"><i class="fas fa-arrow-left"></i> Back to Destination</button>
          <button class="next-btn" id="datesNextBtn" disabled>Continue to Flights <i class="fas fa-arrow-right"></i></button>
        </div>
      </div>

      <!-- Step 3: Flights -->
      <div class="build-step" id="flightsStep">
        <div class="step-header">
          <h2>Choose Your Flights</h2>
          <p>Select the flights that best fit your schedule</p>
        </div>

        <div class="flights-container">
          <!-- Outbound Flights -->
          <div class="flight-section">
            <h3>Outbound Flights</h3>
            <div class="flight-filters">
              <div class="filter-toggle">
                <label class="switch">
                  <input type="checkbox" id="directFlightsOnly">
                  <span class="slider round"></span>
                </label>
                <span>Direct flights only</span>
              </div>
              <div class="sort-by">
                <label>Sort by:</label>
                <select id="flightSortOutbound">
                  <option value="price">Price: Low to High</option>
                  <option value="duration">Duration: Shortest</option>
                  <option value="departure">Departure: Earliest</option>
                  <option value="arrival">Arrival: Earliest</option>
                </select>
              </div>
            </div>

            <div class="flight-cards" id="outboundFlights">
              <!-- Flight cards will be dynamically populated -->
              <div class="flight-card">
                <div class="flight-airline">
                  <img src="https://via.placeholder.com/40" alt="Airline Logo">
                  <span>Air France</span>
                </div>
                <div class="flight-details">
                  <div class="flight-time">
                    <div class="departure">
                      <span class="time">09:15</span>
                      <span class="airport">JFK</span>
                    </div>
                    <div class="flight-duration">
                      <span class="duration">7h 35m</span>
                      <div class="duration-line">
                        <div class="plane-icon"><i class="fas fa-plane"></i></div>
                      </div>
                      <span class="stops">Direct</span>
                    </div>
                    <div class="arrival">
                      <span class="time">21:50</span>
                      <span class="airport">CDG</span>
                    </div>
                  </div>
                </div>
                <div class="flight-price">
                  <span class="price">$420</span>
                  <div class="select-flight">
                    <button class="select-btn">Select</button>
                  </div>
                </div>
              </div>

              <div class="flight-card">
                <div class="flight-airline">
                  <img src="https://via.placeholder.com/40" alt="Airline Logo">
                  <span>Delta</span>
                </div>
                <div class="flight-details">
                  <div class="flight-time">
                    <div class="departure">
                      <span class="time">14:30</span>
                      <span class="airport">JFK</span>
                    </div>
                    <div class="flight-duration">
                      <span class="duration">8h 10m</span>
                      <div class="duration-line">
                        <div class="plane-icon"><i class="fas fa-plane"></i></div>
                      </div>
                      <span class="stops">Direct</span>
                    </div>
                    <div class="arrival">
                      <span class="time">03:40</span>
                      <span class="airport">CDG</span>
                    </div>
                  </div>
                </div>
                <div class="flight-price">
                  <span class="price">$380</span>
                  <div class="select-flight">
                    <button class="select-btn">Select</button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Return Flights -->
          <div class="flight-section">
            <h3>Return Flights</h3>
            <div class="flight-filters">
              <div class="filter-toggle">
                <label class="switch">
                  <input type="checkbox" id="directFlightsOnlyReturn">
                  <span class="slider round"></span>
                </label>
                <span>Direct flights only</span>
              </div>
              <div class="sort-by">
                <label>Sort by:</label>
                <select id="flightSortReturn">
                  <option value="price">Price: Low to High</option>
                  <option value="duration">Duration: Shortest</option>
                  <option value="departure">Departure: Earliest</option>
                  <option value="arrival">Arrival: Earliest</option>
                </select>
              </div>
            </div>

            <div class="flight-cards" id="returnFlights">
              <!-- Return flight cards will be dynamically populated -->
              <div class="flight-card">
                <div class="flight-airline">
                  <img src="https://via.placeholder.com/40" alt="Airline Logo">
                  <span>Air France</span>
                </div>
                <div class="flight-details">
                  <div class="flight-time">
                    <div class="departure">
                      <span class="time">11:25</span>
                      <span class="airport">CDG</span>
                    </div>
                    <div class="flight-duration">
                      <span class="duration">8h 10m</span>
                      <div class="duration-line">
                        <div class="plane-icon"><i class="fas fa-plane"></i></div>
                      </div>
                      <span class="stops">Direct</span>
                    </div>
                    <div class="arrival">
                      <span class="time">14:35</span>
                      <span class="airport">JFK</span>
                    </div>
                  </div>
                </div>
                <div class="flight-price">
                  <span class="price">$450</span>
                  <div class="select-flight">
                    <button class="select-btn">Select</button>
                  </div>
                </div>
              </div>

              <div class="flight-card">
                <div class="flight-airline">
                  <img src="https://via.placeholder.com/40" alt="Airline Logo">
                  <span>Delta</span>
                </div>
                <div class="flight-details">
                  <div class="flight-time">
                    <div class="departure">
                      <span class="time">18:40</span>
                      <span class="airport">CDG</span>
                    </div>
                    <div class="flight-duration">
                      <span class="duration">7h 55m</span>
                      <div class="duration-line">
                        <div class="plane-icon"><i class="fas fa-plane"></i></div>
                      </div>
                      <span class="stops">Direct</span>
                    </div>
                    <div class="arrival">
                      <span class="time">21:35</span>
                      <span class="airport">JFK</span>
                    </div>
                  </div>
                </div>
                <div class="flight-price">
                  <span class="price">$410</span>
                  <div class="select-flight">
                    <button class="select-btn">Select</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="step-navigation">
          <button class="back-btn" id="flightsBackBtn"><i class="fas fa-arrow-left"></i> Back to Dates</button>
          <button class="next-btn" id="flightsNextBtn" disabled>Continue to Hotels <i class="fas fa-arrow-right"></i></button>
        </div>
      </div>

      <!-- Step 4: Hotels -->
      <div class="build-step" id="hotelsStep">
        <div class="step-header">
          <h2>Select Your Accommodation</h2>
          <p>Choose where you want to stay during your trip</p>
        </div>

        <div class="hotels-container">
          <div class="hotel-filters">
            <div class="filter-group">
              <label>Price Range</label>
              <select id="hotelPriceFilter">
                <option value="">Any Price</option>
                <option value="budget">Budget (Under $100/night)</option>
                <option value="moderate">Moderate ($100-$200/night)</option>
                <option value="luxury">Luxury ($200+/night)</option>
              </select>
            </div>
            <div class="filter-group">
              <label>Star Rating</label>
              <select id="hotelStarFilter">
                <option value="">Any Rating</option>
                <option value="3">3+ Stars</option>
                <option value="4">4+ Stars</option>
                <option value="5">5 Stars</option>
              </select>
            </div>
            <div class="filter-group">
              <label>Amenities</label>
              <div class="amenities-filter">
                <label class="amenity-checkbox">
                  <input type="checkbox" value="pool">
                  <span>Pool</span>
                </label>
                <label class="amenity-checkbox">
                  <input type="checkbox" value="wifi">
                  <span>Free WiFi</span>
                </label>
                <label class="amenity-checkbox">
                  <input type="checkbox" value="breakfast">
                  <span>Breakfast</span>
                </label>
                <label class="amenity-checkbox">
                  <input type="checkbox" value="spa">
                  <span>Spa</span>
                </label>
              </div>
            </div>
          </div>

          <div class="hotel-cards" id="hotelList">
            <!-- Hotel cards will be dynamically populated -->
            <div class="hotel-card">
              <div class="hotel-image">
                <img src="https://images.pexels.com/photos/164595/pexels-photo-164595.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Hotel Paris">
                <div class="hotel-rating">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="far fa-star"></i>
                </div>
              </div>
              <div class="hotel-info">
                <h3>Grand Hotel Paris</h3>
                <p class="hotel-location"><i class="fas fa-map-marker-alt"></i> Central Paris, 2km from Eiffel Tower</p>
                <div class="hotel-amenities">
                  <span><i class="fas fa-wifi"></i> Free WiFi</span>
                  <span><i class="fas fa-utensils"></i> Restaurant</span>
                  <span><i class="fas fa-coffee"></i> Breakfast</span>
                </div>
                <div class="hotel-price">
                  <span class="price">$189</span>
                  <span class="per-night">per night</span>
                </div>
                <button class="select-btn">Select Hotel</button>
              </div>
            </div>

            <div class="hotel-card">
              <div class="hotel-image">
                <img src="https://images.pexels.com/photos/1838554/pexels-photo-1838554.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Hotel Paris">
                <div class="hotel-rating">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
              </div>
              <div class="hotel-info">
                <h3>Luxe Parisienne</h3>
                <p class="hotel-location"><i class="fas fa-map-marker-alt"></i> Champs-Élysées, Paris</p>
                <div class="hotel-amenities">
                  <span><i class="fas fa-wifi"></i> Free WiFi</span>
                  <span><i class="fas fa-swimming-pool"></i> Pool</span>
                  <span><i class="fas fa-spa"></i> Spa</span>
                </div>
                <div class="hotel-price">
                  <span class="price">$350</span>
                  <span class="per-night">per night</span>
                </div>
                <button class="select-btn">Select Hotel</button>
              </div>
            </div>

            <div class="hotel-card">
              <div class="hotel-image">
                <img src="https://images.pexels.com/photos/271619/pexels-photo-271619.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Hotel Paris">
                <div class="hotel-rating">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="far fa-star"></i>
                  <i class="far fa-star"></i>
                </div>
              </div>
              <div class="hotel-info">
                <h3>Paris Budget Stay</h3>
                <p class="hotel-location"><i class="fas fa-map-marker-alt"></i> Montmartre, Paris</p>
                <div class="hotel-amenities">
                  <span><i class="fas fa-wifi"></i> Free WiFi</span>
                  <span><i class="fas fa-coffee"></i> Breakfast</span>
                </div>
                <div class="hotel-price">
                  <span class="price">$95</span>
                  <span class="per-night">per night</span>
                </div>
                <button class="select-btn">Select Hotel</button>
              </div>
            </div>
          </div>
        </div>

        <div class="step-navigation">
          <button class="back-btn" id="hotelsBackBtn"><i class="fas fa-arrow-left"></i> Back to Flights</button>
          <button class="next-btn" id="hotelsNextBtn" disabled>Continue to Activities <i class="fas fa-arrow-right"></i></button>
        </div>
      </div>

      <!-- Step 5: Activities -->
      <div class="build-step" id="activitiesStep">
        <div class="step-header">
          <h2>Choose Your Activities</h2>
          <p>Select activities and experiences for your trip</p>
        </div>

        <div class="activities-container">
          <div class="activity-filters">
            <div class="filter-group">
              <label>Category</label>
              <select id="activityCategoryFilter">
                <option value="">All Categories</option>
                <option value="cultural">Cultural</option>
                <option value="adventure">Adventure</option>
                <option value="relaxation">Relaxation</option>
                <option value="food">Food & Drink</option>
                <option value="nature">Nature</option>
              </select>
            </div>
            <div class="filter-group">
              <label>Price Range</label>
              <select id="activityPriceFilter">
                <option value="">Any Price</option>
                <option value="budget">Budget (Under $50)</option>
                <option value="moderate">Moderate ($50-$100)</option>
                <option value="premium">Premium ($100+)</option>
              </select>
            </div>
            <div class="filter-group">
              <label>Duration</label>
              <select id="activityDurationFilter">
                <option value="">Any Duration</option>
                <option value="short">Short (1-3 hours)</option>
                <option value="medium">Medium (3-6 hours)</option>
                <option value="long">Long (6+ hours)</option>
              </select>
            </div>
          </div>

          <div class="activity-selection">
            <div class="activity-list" id="activityList">
              <!-- Activities will be dynamically populated -->
              <div class="activity-card" data-category="cultural">
                <div class="activity-image">
                  <img src="https://images.pexels.com/photos/2363/france-landmark-lights-night.jpg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Eiffel Tower Tour">
                </div>
                <div class="activity-info">
                  <h3>Eiffel Tower Skip-the-Line Tour</h3>
                  <div class="activity-meta">
                    <span><i class="far fa-clock"></i> 2 hours</span>
                    <span><i class="fas fa-user-friends"></i> Small group</span>
                  </div>
                  <p>Skip the long lines and enjoy priority access to the Eiffel Tower with an expert guide.</p>
                  <div class="activity-price-select">
                    <span class="price">$65 per person</span>
                    <button class="select-activity"><i class="fas fa-plus"></i> Add</button>
                  </div>
                </div>
              </div>

              <div class="activity-card" data-category="cultural">
                <div class="activity-image">
                  <img src="https://images.pexels.com/photos/981696/pexels-photo-981696.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Louvre Museum">
                </div>
                <div class="activity-info">
                  <h3>Louvre Museum Guided Tour</h3>
                  <div class="activity-meta">
                    <span><i class="far fa-clock"></i> 3 hours</span>
                    <span><i class="fas fa-user-friends"></i> Small group</span>
                  </div>
                  <p>Discover the masterpieces of the Louvre with an expert art historian guide.</p>
                  <div class="activity-price-select">
                    <span class="price">$55 per person</span>
                    <button class="select-activity"><i class="fas fa-plus"></i> Add</button>
                  </div>
                </div>
              </div>

              <div class="activity-card" data-category="food">
                <div class="activity-image">
                  <img src="https://images.pexels.com/photos/221092/pexels-photo-221092.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="French Cooking Class">
                </div>
                <div class="activity-info">
                  <h3>French Cuisine Cooking Class</h3>
                  <div class="activity-meta">
                    <span><i class="far fa-clock"></i> 4 hours</span>
                    <span><i class="fas fa-utensils"></i> Includes meal</span>
                  </div>
                  <p>Learn to cook classic French dishes with a professional chef in a Parisian kitchen.</p>
                  <div class="activity-price-select">
                    <span class="price">$120 per person</span>
                    <button class="select-activity"><i class="fas fa-plus"></i> Add</button>
                  </div>
                </div>
              </div>

              <div class="activity-card" data-category="relaxation">
                <div class="activity-image">
                  <img src="https://images.pexels.com/photos/3155666/pexels-photo-3155666.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Seine River Cruise">
                </div>
                <div class="activity-info">
                  <h3>Seine River Dinner Cruise</h3>
                  <div class="activity-meta">
                    <span><i class="far fa-clock"></i> 2.5 hours</span>
                    <span><i class="fas fa-glass-cheers"></i> Includes dinner</span>
                  </div>
                  <p>Enjoy a romantic dinner cruise along the Seine River with views of illuminated Paris landmarks.</p>
                  <div class="activity-price-select">
                    <span class="price">$95 per person</span>
                    <button class="select-activity"><i class="fas fa-plus"></i> Add</button>
                  </div>
                </div>
              </div>
            </div>

            <div class="selected-activities">
              <h3>Selected Activities</h3>
              <div id="selectedActivitiesList">
                <!-- Selected activities will be listed here -->
                <p class="no-activities-message">No activities selected yet</p>
              </div>
            </div>
          </div>
        </div>

        <div class="step-navigation">
          <button class="back-btn" id="activitiesBackBtn"><i class="fas fa-arrow-left"></i> Back to Hotels</button>
          <button class="next-btn" id="activitiesNextBtn">Review Your Tour <i class="fas fa-arrow-right"></i></button>
        </div>
      </div>

      <!-- Step 6: Review -->
      <div class="build-step" id="reviewStep">
        <div class="step-header">
          <h2>Review Your Custom Tour</h2>
          <p>Review and finalize your personalized travel itinerary</p>
        </div>

        <div class="review-container">
          <div class="review-summary">
            <div class="summary-header">
              <h3>Your Customized Tour</h3>
              <div class="total-price">
                <span>Total:</span>
                <span class="price" id="totalPrice">$2,345</span>
              </div>
            </div>

            <div class="summary-card destination-summary">
              <div class="summary-icon">
                <i class="fas fa-map-marker-alt"></i>
              </div>
              <div class="summary-details">
                <h4>Destination</h4>
                <p id="reviewDestination">Paris, France</p>
              </div>
              <button class="edit-btn" data-step="destination"><i class="fas fa-edit"></i></button>
            </div>

            <div class="summary-card dates-summary">
              <div class="summary-icon">
                <i class="fas fa-calendar-alt"></i>
              </div>
              <div class="summary-details">
                <h4>Travel Dates</h4>
                <p id="reviewDates">June 15, 2025 - June 22, 2025</p>
                <p id="reviewTravelers">2 Adults, 0 Children, 0 Infants</p>
              </div>
              <button class="edit-btn" data-step="dates"><i class="fas fa-edit"></i></button>
            </div>

            <div class="summary-card flights-summary">
              <div class="summary-icon">
                <i class="fas fa-plane"></i>
              </div>
              <div class="summary-details">
                <h4>Flights</h4>
                <div id="reviewFlights">
                  <div class="flight-summary">
                    <p class="flight-type">Outbound:</p>
                    <p>Delta Airlines - JFK to CDG</p>
                    <p>June 15, 2025 - 14:30 to 03:40 (+1)</p>
                  </div>
                  <div class="flight-summary">
                    <p class="flight-type">Return:</p>
                    <p>Air France - CDG to JFK</p>
                    <p>June 22, 2025 - 11:25 to 14:35</p>
                  </div>
                </div>
              </div>
              <button class="edit-btn" data-step="flights"><i class="fas fa-edit"></i></button>
            </div>

            <div class="summary-card hotel-summary">
              <div class="summary-icon">
                <i class="fas fa-hotel"></i>
              </div>
              <div class="summary-details">
                <h4>Accommodation</h4>
                <div id="reviewHotel">
                  <p>Grand Hotel Paris (4★)</p>
                  <p>Central Paris, 2km from Eiffel Tower</p>
                  <p>7 nights at $189 per night</p>
                </div>
              </div>
              <button class="edit-btn" data-step="hotels"><i class="fas fa-edit"></i></button>
            </div>

            <div class="summary-card activities-summary">
              <div class="summary-icon">
                <i class="fas fa-hiking"></i>
              </div>
              <div class="summary-details">
                <h4>Activities</h4>
                <div id="reviewActivities">
                  <ul>
                    <li>Eiffel Tower Skip-the-Line Tour - $65 per person</li>
                    <li>Seine River Dinner Cruise - $95 per person</li>
                  </ul>
                </div>
              </div>
              <button class="edit-btn" data-step="activities"><i class="fas fa-edit"></i></button>
            </div>
          </div>

          <div class="booking-sidebar">
            <div class="price-breakdown">
              <h4>Price Breakdown</h4>
              <div class="price-item">
                <span>Flights</span>
                <span id="flightsCost">$790</span>
              </div>
              <div class="price-item">
                <span>Accommodation</span>
                <span id="hotelCost">$1,323</span>
              </div>
              <div class="price-item">
                <span>Activities</span>
                <span id="activitiesCost">$320</span>
              </div>
              <div class="price-item taxes">
                <span>Taxes & Fees</span>
                <span id="taxesCost">$112</span>
              </div>
              <div class="price-item total">
                <span>Total</span>
                <span id="sidebarTotalPrice">$2,545</span>
              </div>
            </div>
            <div class="booking-actions">
              <button class="book-btn">Book Your Tour</button>
              <button class="save-btn">Save Itinerary</button>
              <p class="booking-note">No payment required until final confirmation</p>
            </div>
          </div>
        </div>

        <div class="step-navigation">
          <button class="back-btn" id="reviewBackBtn"><i class="fas fa-arrow-left"></i> Back to Activities</button>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Newsletter Section -->
<!--<section class="newsletter-section">-->
<!--  <div class="container">-->
<!--    <div class="newsletter-content" data-aos="fade-up">-->
<!--      <h2>Get Travel Deals & Updates</h2>-->
<!--      <p>Subscribe to our newsletter and never miss out on our exclusive deals</p>-->
<!--      <form class="newsletter-form">-->
<!--        <input type="email" placeholder="Your email address">-->
<!--        <button type="submit">Subscribe</button>-->
<!--      </form>-->
<!--    </div>-->
<!--  </div>-->
<!--</section>-->

<?php include 'footer.php'; ?>




<script>
    // Initialize AOS Animation
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({duration:1000, once:true});

        // Toggle menu
        document.getElementById("hamburger").addEventListener("click", () => {
            document.getElementById("navLinks").classList.toggle("show");
        });

        // Fixed header on scroll
        const mainHeader = document.getElementById("mainHeader");
        const headerHeight = mainHeader.offsetHeight;

        window.addEventListener("scroll", () => {
            if (window.scrollY > 100) {
                mainHeader.classList.add("fixed");
                document.body.style.paddingTop = headerHeight + "px";
            } else {
                mainHeader.classList.remove("fixed");
                document.body.style.paddingTop = 0;
            }
        });

        // Back to Top Button functionality
        const backToTopBtn = document.createElement('button');
        backToTopBtn.id = 'backToTop';
        backToTopBtn.innerHTML = '<i class="fa-solid fa-arrow-up"></i>';
        document.body.appendChild(backToTopBtn);

        window.addEventListener("scroll", () => {
            backToTopBtn.style.display = window.scrollY > 300 ? "block" : "none";
        });

        backToTopBtn.addEventListener("click", () => {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });

        // Modal functionality
        const modal = document.getElementById('authModal');
        const loginBtn = document.getElementById('loginBtn');
        const closeBtn = document.getElementsByClassName('close')[0];

        // Only attach if elements exist
        if (loginBtn) {
            loginBtn.addEventListener('click', function(e) {
                e.preventDefault();
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden'; // Prevent scrolling
            });
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                closeModal();
            });
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target == modal) {
                closeModal();
            }
        });

        // Close modal function
        function closeModal() {
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = ''; // Re-enable scrolling
            }
        }

        // Initialize auto-hiding alerts
        const alerts = document.querySelectorAll('.auth-alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.classList.add('fade-out');
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }, 5000);
        });
    });

    // Tab functionality
    function openTab(evt, tabName) {
        // Declare variables
        let i, tabcontent, tablinks;

        // Get all elements with class="tabcontent" and hide them
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].classList.remove("active");
        }

        // Get all elements with class="tablinks" and remove the class "active"
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active");
        }

        // Show the current tab, and add an "active" class to the button that opened the tab
        document.getElementById(tabName).classList.add("active");
        evt.currentTarget.classList.add("active");
    }
</script>

<!-- Back to Top Button (will be added via JavaScript) -->

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script src="js/custom.js"></script>
<script src="js/custom-tour.js"></script>
</body>
</html>