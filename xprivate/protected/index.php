<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>participant preview page</title>
  <link rel="stylesheet" href="table.css">

</head>
<body>
  <div class="toast">
  </div>
  <header>
    <h1>Hi, this is vesnasoft_participants table</h1>
  </header>
  <div class="participant-table"  >
    <table id='prtcpnts' class='dataTable display cell-border compact'>
      <thead>
        <tr>
          <th>id</th>
          <th>name_surname</th>
          <th>age</th>
          <th>phone</th>
          <th>email</th>
          <th>lang</th>
          <th>project_title</th>
          <th>project_description</th>
          <th>team_id</th>
          <th>created_at</th>
        </tr>
      </thead>
      <!-- <tfoot>
        <tr>
          <td colspan="4">
            <div id="paging">
              <ul>
                <li><a href="#"><span>Previous</span></a></li>
                <li><a href="#" class="active"><span>1</span></a></li>
                <li><a href="#"><span>2</span></a></li>
                <li><a href="#"><span>3</span></a></li>
                <li><a href="#"><span>4</span></a></li>
                <li><a href="#"><span>5</span></a></li>
                <li><a href="#"><span>Next</span></a></li>
              </ul>
            </div>
        </tr>
      </tfoot> -->
      <tbody>

  <?php
    include '../db.php';
    $query = "SELECT * FROM  `vesnasoft_participants` ORDER BY  `team_id` ";
    $participants = mysqli_query($con, $query);

    if ($participants->num_rows > 0) {
      $n = 1;
    	while ($row = mysqli_fetch_array($participants, MYSQL_ASSOC)) {
        ?>
        <tr <?php if( $n%2 == 1 ) echo 'class="alt";'?>>
          <td><?=$row['id']?></td>
          <td><?=$row['name_surname']?></td>
          <td><?=$row['age']?></td>
          <td><?=$row['phone']?></td>
          <td><?=$row['email']?></td>
          <td><?=$row['lang']?></td>
          <td><?=$row['project_title']?></td>
          <td><?=$row['project_description']?></td>
          <td class='team-id-td'>
            <span class='team-id-span' style="display:none"><?=$row['team_id']?></span>
            <input min="0" type="number" name="team-number" data-id="<?=$row['team_id']?>" value="<?=$row['team_id']?>" class='team-id-input'>
            <!-- <input type="button" name="reset" data-id="<?=$row['id']?>" value='reset' class='reset-button team-id-buttons'> -->
            <input type="button" name="apply" data-id="<?=$row['id']?>" value='apply' class='apply-button team-id-buttons'>
          </td>
          <td><?=$row['created_at']?></td>
        </tr>
        <?php
        $n++;
      }
    }
 ?>
    </tbody>
  </table>
</div>
<link rel="stylesheet" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css" media="screen" title="no title" charset="utf-8">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js" charset="utf-8"></script>
<!-- <script src="jquery.tablesorter.js" charset="utf-8"></script> -->

<script src="teamIdChanger.js"></script>
<script type="text/javascript">
$(document).ready(function() {
  $('#prtcpnts').DataTable({
    dom: 'T<"clear">lfrtip',
       tableTools: {
           "sRowSelect": "single"
       },
      "lengthMenu": [[25, 50, -1], [25, 50, "All"]]

  });
} );

</script>

</body>
</html>

<?php
// $ta "<p>Full path to a .htpasswd file in this dir: " . $dir . "/.htpasswd" . "</p>";
?>
