
<?PHP
 include 'header.inc';
?>
    <h1 class="sub-header" >Manage Rooms</h1><br>
      <ul class="nav nav-pills">
        <li class="active"><a data-toggle="tab" href="#modify_room"><span class="glyphicon glyphicon-wrench"></span>  Modify Rooms</a></li>
        <li><a data-toggle="tab" href="#add_room"><span class="glyphicon glyphicon-plus"></span>  Add Rooms</a></li>
        <li><a data-toggle="tab" href="#add_room_type"><span class="glyphicon glyphicon-plus"></span>  New Rooms Type</a></li>

    </ul>
    <div class="tab-content">
        <div id="modify_room" class="tab-pane fade in active">
          <div class="table-responsive">
            <form class="form-signin" method="POST"  action="manage_room.php">
    <select id="drop2r" name="manage_room_type" class="form-control" title="Room Type" onchange="manage_room_details()" required>
      <option disabled selected>Room Type</option>

    </select>
        <input type="text"  id="manage_current_cost" name="manage_current_cost" class="form-control" placeholder="Current Room Cost" readonly >
    <input type="text"  id="manage_new_cost" name="manage_new_cost" class="form-control" placeholder="New Room Cost" required>
        <br>
    <input type="submit" name="submit" class="btn btn-lg btn-primary btn-block" value="Update Details" ></input>
      </form>
          </div>
        </div>
			</div>
