<div class="admin-header">
    <h2><i class="fas fa-hiking"></i> Tours Management</h2>
    <div class="admin-actions">
        <button class="btn btn-primary" data-modal-target="add-tour-modal">
            <i class="fas fa-plus"></i> Add New Tour
        </button>
    </div>
</div>

<div class="search-filter">
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" class="search-input" placeholder="Search tours..." data-table-search="tours-table">
    </div>

    <!--  <select class="filter-select" data-table-filter="tours-table">
          <option value="">All Continents</option>
          <option value="europe">Europe</option>
          <option value="asia">Asia</option>
          <option value="africa">Africa</option>
          <option value="north-america">North America</option>
          <option value="south-america">South America</option>
          <option value="australia">Australia & Oceania</option>
      </select>-->
</div>

<div class="data-table-wrapper">
    <table class="data-table" id="tours-table">
        <thead>
        <tr>
            <th>Tour ID</th>
            <th>Tour Name</th>
            <th>Destination</th>
            <th>Price</th>
            <th>Rating</th>
            <th>Duration</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <!-- <tr data-category="europe">
             <td>TOUR001</td>
             <td>Name 1</td>
             <td>Paris Explorer (Paris, France)</td>
             <td>$1,899</td>
             <td>4.5</td>
             <td>5 days</td>
             <td>
                 <div class="action-btns">
                     <button class="action-btn view" title="View Tour Image" data-modal-target="view-tour-image-modal">
                         <i class="fas fa-image"></i>
                     </button>
                     <button class="action-btn edit" title="Edit Tour" data-modal-target="edit-tour-modal">
                         <i class="fas fa-edit"></i>
                     </button>
                     <button class="action-btn delete delete-btn" title="Delete Tour">
                         <i class="fas fa-trash-alt"></i>
                     </button>
                 </div>
             </td>
         </tr>
         <tr data-category="europe">
             <td>TOUR002</td>
             <td>Roman Holiday (Rome, Italy)</td>
             <td>$2,199</td>
             <td>4.0</td>
             <td>6 days</td>
             <td>
                 <div class="action-btns">
                     <button class="action-btn view" title="View Tour Image" data-modal-target="view-tour-image-modal">
                         <i class="fas fa-image"></i>
                     </button>
                     <button class="action-btn edit" title="Edit Tour" data-modal-target="edit-tour-modal">
                         <i class="fas fa-edit"></i>
                     </button>
                     <button class="action-btn delete delete-btn" title="Delete Tour">
                         <i class="fas fa-trash-alt"></i>
                     </button>
                 </div>
             </td>
         </tr>
         <tr data-category="europe">
             <td>TOUR003</td>
             <td>Greek Island Hopping (Athens, Greece)</td>
             <td>$2,899</td>
             <td>4.9</td>
             <td>10 days</td>
             <td>
                 <div class="action-btns">
                     <button class="action-btn view" title="View Tour Image" data-modal-target="view-tour-image-modal">
                         <i class="fas fa-image"></i>
                     </button>
                     <button class="action-btn edit" title="Edit Tour" data-modal-target="edit-tour-modal">
                         <i class="fas fa-edit"></i>
                     </button>
                     <button class="action-btn delete delete-btn" title="Delete Tour">
                         <i class="fas fa-trash-alt"></i>
                     </button>
                 </div>
             </td>
         </tr>
         <tr data-category="asia">
             <td>TOUR004</td>
             <td>Japan Heritage Tour (Tokyo, Japan)</td>
             <td>$3,599</td>
             <td>4.7</td>
             <td>12 days</td>
             <td>
                 <div class="action-btns">
                     <button class="action-btn view" title="View Tour Image" data-modal-target="view-tour-image-modal">
                         <i class="fas fa-image"></i>
                     </button>
                     <button class="action-btn edit" title="Edit Tour" data-modal-target="edit-tour-modal">
                         <i class="fas fa-edit"></i>
                     </button>
                     <button class="action-btn delete delete-btn" title="Delete Tour">
                         <i class="fas fa-trash-alt"></i>
                     </button>
                 </div>
             </td>
         </tr>
         <tr data-category="asia">
             <td>TOUR005</td>
             <td>Thailand Beach & Culture (Bangkok, Thailand)</td>
             <td>$999</td>
             <td>4.2</td>
             <td>7 days</td>
             <td>
                 <div class="action-btns">
                     <button class="action-btn view" title="View Tour Image" data-modal-target="view-tour-image-modal">
                         <i class="fas fa-image"></i>
                     </button>
                     <button class="action-btn edit" title="Edit Tour" data-modal-target="edit-tour-modal">
                         <i class="fas fa-edit"></i>
                     </button>
                     <button class="action-btn delete delete-btn" title="Delete Tour">
                         <i class="fas fa-trash-alt"></i>
                     </button>
                 </div>
             </td>
         </tr>-->
        </tbody>
    </table>
</div>

<div class="pagination">
    <button class="page-btn prev"><i class="fas fa-chevron-left"></i> Previous</button>
    <button class="page-btn active">1</button>
    <button class="page-btn">2</button>
    <button class="page-btn">3</button>
    <button class="page-btn next">Next <i class="fas fa-chevron-right"></i></button>
</div>

<!-- Add Tour Modal -->
<div class="modal-overlay" id="add-tour-modal">
    <div class="modal">
        <div class="modal-header">
            <h3>Add New Tour</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <form id="add-tour-form" data-action="added" data-table="tours-table" method="post" action="../Actions/addTour.php" >
                <div class="form-grid">

                    <div class="form-group">
                        <label for="tourname">Tour Name</label>
                        <input type="text" id="tourname" name="tourname" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="destid">Destination ID</label>
                        <input type="text" id="destid" name="destid"  class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="flightid">Flight ID</label>
                        <input type="text" id="flightid" name="flightid" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="hotelid">Hotel ID</label>
                        <input type="text" id="hotelid" name="hotelid"  class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" id="price" name="price" class="form-control" min="0" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label for="rating">Rating</label>
                        <input type="number" id="rating" name="rating" class="form-control" min="0" max="5" step="0.1" required>
                    </div>

                    <div class="form-group">
                        <label for="duration">Duration (days)</label>
                        <input type="number" id="duration"  name="duration" class="form-control" min="1" required>
                    </div>


                </div>

                <!--  <div class="form-group">
                      <label for="tour-image">Tour Image</label>
                      <div class="image-upload">
                          <input type="file" id="tour-image" accept="image/*" style="display: none;">
                          <div class="image-upload-icon">
                              <i class="fas fa-cloud-upload-alt"></i>
                          </div>
                          <div class="image-upload-text">Click to upload an image</div>
                          <div class="image-upload-help">JPEG, PNG or GIF, Maximum 5MB</div>
                      </div>
                      <div class="image-preview"></div>
                  </div>-->

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modal-cancel">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Tour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Tour Modal -->
<div class="modal-overlay" id="edit-tour-modal">
    <div class="modal">
        <div class="modal-header">
            <h3>Edit Tour</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <form id="edit-tour-form" data-action="updated">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="edit-tourid">Tour ID</label>
                        <input type="text" id="edit-tourid" class="form-control" value="TOUR001" readonly>
                    </div>

                    <div class="form-group">
                        <label for="edit-dest-id">Destination ID</label>
                        <input type="text" id="edit-dest-id" class="form-control" value="DEST001" required>
                    </div>

                    <div class="form-group">
                        <label for="edit-flight-id">Flight ID</label>
                        <input type="text" id="edit-flight-id" class="form-control" value="FLT001" required>
                    </div>

                    <div class="form-group">
                        <label for="edit-hotel-id">Hotel ID</label>
                        <input type="text" id="edit-hotel-id" class="form-control" value="HTL001" required>
                    </div>

                    <div class="form-group">
                        <label for="edit-price">Price</label>
                        <input type="number" id="edit-price" class="form-control" value="1899" min="0" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label for="edit-rating">Rating</label>
                        <input type="number" id="edit-rating" class="form-control" value="4.5" min="0" max="5" step="0.1" required>
                    </div>

                    <div class="form-group">
                        <label for="edit-duration">Duration (days)</label>
                        <input type="number" id="edit-duration" class="form-control" value="5" min="1" required>
                    </div>

                    <div class="form-group">
                        <label for="edit-tour-name">Tour Name</label>
                        <input type="text" id="edit-tour-name" class="form-control" value="Paris Explorer" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="edit-tour-image">Tour Image</label>
                    <div class="image-upload">
                        <input type="file" id="edit-tour-image" accept="image/*" style="display: none;">
                        <div class="image-upload-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <div class="image-upload-text">Click to upload a new image</div>
                        <div class="image-upload-help">JPEG, PNG or GIF, Maximum 5MB</div>
                    </div>
                    <div class="image-preview">
                        <img src="https://images.pexels.com/photos/532826/pexels-photo-532826.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Paris Explorer">
                        <div class="image-preview-remove">
                            <i class="fas fa-times"></i>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modal-cancel">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Tour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Tour Image Modal -->
<div class="modal-overlay" id="view-tour-image-modal">
    <div class="modal">
        <div class="modal-header">
            <h3>Tour Image</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body" style="text-align: center;">
            <h4 style="margin-top: 0;">Paris Explorer</h4>
            <img src="https://images.pexels.com/photos/532826/pexels-photo-532826.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Paris Explorer" style="max-width: 100%; border-radius: 8px; margin-bottom: 20px;">

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary modal-cancel">Close</button>
                <button type="button" class="btn btn-primary" data-modal-target="edit-tour-modal">Edit Tour</button>
            </div>
        </div>
    </div>
</div>