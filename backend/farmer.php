<?php
require_once 'session.php';
requireLogin();
$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../frontend/css/farmer.css">
    <link rel="stylesheet" href="../frontend/css/admin.css">

    <title>Farmer Management</title>
  </head>
  <body>
    <div class="page-wrapper">
        <div class="sidebar">
            <div class="sidebar-header"><h3><i class="fas fa-cow"></i> Pooja's Dairy</h3></div>
            <a href="dashboard.php" class="nav-item"><i class="fas fa-home"></i> Dashboard</a>
            <a href="farmers.php" class="nav-item active"><i class="fas fa-users"></i> Farmers</a>
            <a href="animals.php" class="nav-item"><i class="fas fa-cow"></i> Animals</a>
            <a href="milk-collection.php" class="nav-item"><i class="fas fa-tint"></i> Milk Collection</a>
            <a href="rates.php" class="nav-item"><i class="fas fa-percentage"></i> Rates</a>
            <a href="billing.php" class="nav-item"><i class="fas fa-receipt"></i> Billing</a>
            <a href="bill-history.php" class="nav-item"><i class="fas fa-history"></i> Bill History</a>
            <a href="reports.php" class="nav-item"><i class="fas fa-chart-bar"></i> Reports</a>
            <a href="settings.php" class="nav-item"><i class="fas fa-cog"></i> Settings</a>
            <a href="logout.php" class="nav-item"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
        <div class="main-content">
            <div class="topbar" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 class="page-title"><i class="fas fa-users"></i> Farmer Management</h2>
                    <p class="small-note">Add, edit, and manage farmer profiles from one screen.</p>
                </div>
                <div class="user-info" style="text-align: right;">
                    <div class="user-name"><?php echo htmlspecialchars($user['name']); ?></div>
                    <div class="user-role"><?php echo ucfirst($user['role']); ?></div>
                </div>
            </div>

            <div class="page-inner">
                <button onclick="document.getElementById('id01').style.display='block'" class="btn-primary" style="margin-bottom: 1.5rem;"><i class="fas fa-user-plus"></i> New Farmer</button>
                <div id="id01" class="modal">
                  <form class="modal-content animate" action="connect.php" method="POST">
                    <h3><b><u>Add new farmer</u></b></h3>
                    <div class="container">
                      <label for="name">First Name <sup>*</sup></label>
                      <input type="text" name="firstname" placeholder="Enter name of farmer" required />

                      <label for="ph">Phone number <sup>*</sup></label>
                      <input type="text" name="ph" placeholder="Phone Number" maxlength="10" required />

                      <label for="vid">Village ID <sup>*</sup></label>
                      <input type="text" name="vid" placeholder="Village" required />

                      <label for="milk_type">Milk Type <sup>*</sup></label>
                      <input type="text" name="milk_type" placeholder="cow or Buffalo" required />

                      <label for="min_litre">Minimum Litre <sup>*</sup></label>
                      <input type="number" name="min_litre" placeholder="/day" maxlength="2" required />

                      <label for="animalID">Animal Health ID <sup>*</sup></label>
                      <input type="text" name="animalID" placeholder="Issued by Health Ministry" maxlength="5" required />

                      <input type="submit" name="savedata" class="submit-btn-add" value="Submit">
                    </div>
                    <div class="container" style="background-color:#f1f1f1">
                      <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Cancel</button>
                    </div>
                  </form>
                </div>
<!--################################################################################################################################################-->
<!--Edit Modal-->

<!--################################################################################################################################################-->
<!--Delete Modal!-->

<!--################################################################################################################################################-->
  <!--Fetch Details &  display-->
    <div class="card">
      <div class="card-body">
    <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "dry";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
            }
            $sql = "SELECT * FROM farmer ";
            $query_run = mysqli_query($conn, $sql);
      ?>

        <table class="table table-dark table-hover">
      <thead>
        <tr>
          <th scope="col"><i class="fas fa-id-badge"></i>  ID</th>
          <th scope="col"><i class="fas fa-user-alt"></i> Firstname</th>

          <th scope="col"><i class="fas fa-phone"></i> Phone</th>
          <th scope="col"><i class="fas fa-address-book"></i> village id</th>
          <th scope="col"> <i class="fas fa-caret-square-down"></i> More</th>


        </tr>

      </thead>
      <?php
        if ($query_run)
        {
            foreach ($query_run as $row)
            {
      ?>

        <tbody>
          <tr>
            <td> <?php echo $row['id']; ?> </td>
            <td> <?php echo $row['fname']; ?> </td>
            <td> <?php echo $row['ph']; ?> </td>
            <td> <?php echo $row['f_vid']; ?> </td>
            <td>
              <div class="btn-group">
                <a  class="btn btn-secondary" href="edit.php?id=<?php echo $row['id']; ?>" ><i class="fas fa-edit"></i>  Edit</a>
                <a class="btn btn-danger" onClick="javascript:return confirm('Do you really want to delete?');" href="delete.php?id=<?php echo $row['id']; ?>"><i class="fas fa-trash-alt"></i>  Delete</a>
              </div>
            </td>

          </tr>
        </tbody>
        <?php
            }
          }
          else {
            echo "No Record Found";
          }

        ?>

    </table>
  </div>
</div>
</div>
    <hr>
    <hr>


    <div class="footer">
      <p>&copy DIARY.com <sub>All Rigths Reserved</sub> </p>
    </div>
    <script src="css/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>


    <script>

    // Get the modal
    var modal = document.getElementById('id01');

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

function edit()
{
  var modal1 = document.getElementById('id02');
  modal1.style.display='block';

}
</script>



  </body>
</html>
