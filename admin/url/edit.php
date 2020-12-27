<?php 
    include '../../config/dbconnection.php';

    $fname = '';
    $lname = '';
    $mname = '';
    $rtype = '';
    $rdesc = '';
    $uname = '';
    $psswd = '';
    $param_id = '';
    if(isset($_GET['id'])) {
        $sql_s_j = "SELECT ud.Firstname, ud.Lastname, ud.MI, ud.RoleType, ud.RoleDescription,
        u.Username, u.Password
        FROM administrator u
        INNER JOIN administrator_details ud ON u.ID = ud.ID WHERE u.ID = ?";
        if($stmt = mysqli_prepare($conn, $sql_s_j)) {
            $param_id = trim($_GET['id']);
            mysqli_stmt_bind_param($stmt, 'i', $param_id);
            if(mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                if(mysqli_num_rows($result) == 1) {
                    /* Fetch result row as an associative array. 
                    Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $fname = $row['Firstname'];
                    $lname = $row['Lastname'];
                    $mname = $row['MI'];
                    $rtype = $row['RoleType'];
                    $rdesc = $row['RoleDescription'];
                    $uname = $row['Username'];
                    $psswd = $row['Password'];
                }
            }
            mysqli_stmt_close($stmt);
        }else {
            echo "
                <script type='text/javascript'> 
                    setTimeout(function(){
                        alert('Invalid URL parameter!');
                        window.location.replace('http://localhost/vg-shipping-lines/admin/assign.php');
                    }, 300);
                </script>
            ";
            exit;
        }
    }
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $fname = '';
        $lname = '';
        $mname = '';
        $rtype = '';
        $rdesc = '';
        $uname = '';
        $psswd = '';
        $id = trim($_POST['id']);
        $firstname = trim($_POST['fname']);
        $lastname = trim($_POST['lname']);
        $role_type = trim($_POST['rtype']);
        $middlename = trim($_POST['mname']);
        $description = trim($_POST['rdescription']);
        $username = trim($_POST['uname']);
        $password = trim($_POST['password']);
        editInfoAdministrator($conn, $firstname, $lastname,  $middlename, $role_type, $description, $username, $password, $id);
    }
    function editInfoAdministrator($c, $f, $l, $m, $r, $d, $u, $p, $i) {
        $sql_update = "UPDATE administrator a 
                        INNER JOIN administrator_details ad ON a.ID=ad.ID
                        SET ad.Firstname=?, ad.Lastname=?, ad.MI=?, ad.RoleType=?, ad.RoleDescription=?, a.Username=?, a.Password=?
                        WHERE a.ID=?";
        if($stmt = mysqli_prepare($c, $sql_update)) {
            mysqli_stmt_bind_param($stmt, 'sssssssi', $f, $l, $m, $r, $d, $u, $p, $i);
            if(mysqli_stmt_execute($stmt)) {
                echo "
                    <script type='text/javascript'>
                        setTimeout(function() {
                            alert('Updated successfully.');
                            window.location.replace('http://localhost/vg-shipping-lines/admin/assign.php');
                        }, 300);
                    </script>
                ";
                exit;
            }else {
                echo "
                    <script type='text/javascript'>
                        setTimeout(function() {
                            alert('Something went wrong, try again later.');
                            window.location.replace('http://localhost/vg-shipping-lines/admin/assign.php');
                        }, 300);
                    </script>
                ";
                exit;
            }
            mysqli_stmt_close($stmt);
        }
    }
?>
<style type="text/css">
    .form-style-2{
        margin: auto;
        max-width: 500px;
        padding: 20px 12px 10px 20px;
        font: 13px Arial, Helvetica, sans-serif;
    }
    .form-style-2-heading{
        letter-spacing: 0;
        font-weight: bold;
        margin-bottom: 20px;
        font-size: 15px;
        padding-bottom: 3px;
    }
    .form-style-2 label{
        display: block;
        margin: 0px 0px 15px 0px;
    }
    .form-style-2 label > span{
        width: 100px;
        font-weight: 600;
        float: left;
        padding-top: 8px;
        padding-right: 5px;
    }
    .form-style-2 span.required{
        color:red;
    }
    .form-style-2 .tel-number-field{
        width: 40px;
        text-align: center;
    }
    .form-style-2 input.input-field, .form-style-2 .select-field{
        width: 48%;	
    }
    .form-style-2 input.input-field, 
    .form-style-2 .select-field{
        box-sizing: border-box;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        border: 1px solid #C2C2C2;
        box-shadow: 1px 1px 4px #EBEBEB;
        -moz-box-shadow: 1px 1px 4px #EBEBEB;
        -webkit-box-shadow: 1px 1px 4px #EBEBEB;
        border-radius: 3px;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        padding: 7px;
        outline: none;
        font-size: 0.719rem;
        color: #C2C2C2;
    }
    .form-style-2 .input-field:focus, 
    .form-style-2 .select-field:focus{
        border: 1px solid #0C0;
    }
    .form-style-2 input[type=submit],
    .form-style-2 input[type=button]{
        border: none;
        padding: 8px 15px 8px 15px;
        background: #FF8500;
        color: #fff;
        box-shadow: 1px 1px 4px #DADADA;
        -moz-box-shadow: 1px 1px 4px #DADADA;
        -webkit-box-shadow: 1px 1px 4px #DADADA;
        border-radius: 3px;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
    }
    .form-style-2 input[type=submit]:hover,
    .form-style-2 input[type=button]:hover{
        background: #EA7B00;
        color: #fff;
    }
</style>
<div class="form-style-2">
    <div class="form-style-2-heading">Modify information of <?php echo $rtype; ?>?</div>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <label for="field1" style="display: none !important;">
            <span>ID<span class="required">*</span></span>
            <input type="number" class="input-field" name="id" value="<?php echo $param_id; ?>"/>
        </label>
        <label for="field1">
            <span>Firstname<span class="required">*</span></span>
            <input type="text" class="input-field" name="fname" value="<?php echo $fname; ?>" required/>
        </label>
        <label for="field2">
            <span>Lastname<span class="required">*</span></span>
            <input type="text" class="input-field" name="lname" value="<?php echo $lname; ?>" required/>
        </label>
        <label for="field2">
            <span>Middlename<span class="required">*</span></span>
            <input type="text" class="input-field" name="mname" value="<?php echo $mname; ?>" required/>
        </label>
        <label for="field4">
            <span>Role Type<span class="required">*</span></span>
            <select name="rtype" class="select-field" required>
                <option value="<?php echo $rtype; ?>" style="display:none;">
                    <?php echo $rtype; ?>
                </option>
                <option value="Admin">Admin</option>
                <option value="Ticket-In-Charge">Ticket-In-Charge</option>
            </select>
        </label>
        <label for="field1">
            <span>Description<span class="required">*</span></span>
            <input type="text" class="input-field" name="rdescription" value="<?php echo $rdesc; ?>" required/>
        </label>
        <label for="field1">
            <span>Username<span class="required">*</span></span>
            <input type="text" class="input-field" name="uname" value="<?php echo $uname; ?>" required/>
        </label>
        <label for="field1">
            <span>Password<span class="required">*</span></span>
            <input type="password" class="input-field" name="password" value="<?php echo $psswd; ?>" required/>
        </label>
        <label>
            <span></span>
            <input type="submit" value="Submit" />
        </label>
    </form>
</div>