<?php
include("../fheader.php");
if (isset($_SESSION["isLogedIn"]) && $_SESSION["isLogedIn"] == false) {
?>
    <script>
        alert("Please login first...");
        location.href = "<?= $_SESSION["directory"] ?>login.php";
    </script>
<?php
}

$cropID = $_GET["id"];

//deposit amount checking
$userID = $_SESSION["userid"];
$sql2 = "SELECT SUM(amount) as amount FROM `deposit` WHERE userID='$userID' AND role=1";
$result1 = $con->query($sql2);
if ($result1->num_rows > 0) {
    foreach ($result1 as $row1) {
        $d_amount = $row1["amount"];
    }
} else {
    $d_amount = 0;
}
if ($d_amount == "") {
    $d_amount = 0;
}
?>

<!-- Start Breadcrumb -->
<div class="page-heading">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center">
                <h1 class="title"><span>Crop Detail</span></h1>
                <div class="breadcrumb">
                    <a href="http://localhost/farm1/home.php">Home</a>
                    <span class="delimeter">/</span>
                    <span class="current">Crop Detail</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Breadcrumb -->

<section class="main-contain shop-product bg-white" style="padding-bottom: 0px;">
    <div class="container">
        <div>
            <?php if (isset($_SESSION["bidmsg"])) { ?>
                <div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong><?= $_SESSION["bidmsg"] ?></strong>
                </div>
                <script>
                    window.setTimeout(function() {
                        $(".alert").fadeTo(500, 0).slideUp(500, function() {
                            $(this).remove();
                        });
                    }, 4000);
                </script>

            <?php
                unset($_SESSION["bidmsg"]);
            }

            ?>
        </div>
        <div class="row">
            <!--Image Block-->
            <div class="signal-product-image">
                <div class="col-md-6 col-sm-6 col-sm-12">
                    <div class="images cd-post-thumbnail">
                        <div class="flexslider-wrap product-image-full-size">
                            <div class="flexslider" id="main_slider">
                                <div class="flex-viewport">
                                    <ul class="slides">
                                        <?php
                                        $sql = "SELECT * FROM `vehicleimage` WHERE `vehicleID`='$cropID'";
                                        $result1 = $con->query($sql);
                                        if ($result1->num_rows > 0) {
                                            foreach ($result1 as $row) {
                                        ?>
                                                <li itemtype="https://schema.org/Product" itemscope="">
                                                    <a class="cd-lightbox-image cd-lightbox-type-image" href="<?= $_SESSION["directory"] ?>img/vehicle/<?= $row["name"] ?>" title="">
                                                        <img title="" style="height: 450px; width: 413px;" src="<?= $_SESSION["directory"] ?>img/vehicle/<?= $row["name"] ?>" alt="">
                                                    </a>
                                                </li>
                                                <li itemtype="https://schema.org/Product" itemscope="">
                                                    <a class="cd-lightbox-image cd-lightbox-type-image" href="<?= $_SESSION["directory"] ?>img/vehicle/<?= $row["name2"] ?>" title="">
                                                        <img title="" style="height: 450px; width: 413px;" src="<?= $_SESSION["directory"] ?>img/vehicle/<?= $row["name2"] ?>" alt="">
                                                    </a>
                                                </li>
                                                <li itemtype="https://schema.org/Product" itemscope="">
                                                    <a class="cd-lightbox-image cd-lightbox-type-image" href="<?= $_SESSION["directory"] ?>img/vehicle/<?= $row["name3"] ?>" title="">
                                                        <img title="" style="height: 450px; width: 413px;" src="<?= $_SESSION["directory"] ?>img/vehicle/<?= $row["name3"] ?>" alt="">
                                                    </a>
                                                </li>

                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="flexslider" id="main_thumbs">
                            <div class="flex-viewport">
                                <ul class="slides">
                                    <li itemtype="https://schema.org/Product" itemscope="">
                                        <img title="" style="height: 100px; width: 170px;" alt="" src="<?= $_SESSION["directory"] ?>img/vehicle/<?= $row["name"] ?>">
                                    </li>
                                    <li itemtype="https://schema.org/Product" itemscope="">
                                        <img title="" style="height: 100px; width: 170px;" alt="" src="<?= $_SESSION["directory"] ?>img/vehicle/<?= $row["name2"] ?>">
                                    </li>
                                    <li itemtype="https://schema.org/Product" itemscope="">
                                        <img title="" style="height: 100px; width: 170px;" alt="" src="<?= $_SESSION["directory"] ?>img/vehicle/<?= $row["name3"] ?>">
                                    </li>

                                </ul>
                            </div>
                        </div>
                <?php  }
                                        } ?>


                    </div>
                </div>
            </div>
            <!--Image Block-->


            <?php
            $sql2 = "SELECT * FROM `vehicle` WHERE `ID`='$cropID'";
            $result2 = $con->query($sql2);
            if ($result2->num_rows > 0) {
                foreach ($result2 as $row) {
                    $modelname = $row["name"];
                    $type1 = $row["type"];
                    $catagory = $row["catagory"];
                    $startDate = $row["startDate"];
                    $EndDate = $row["EndDate"];

                    $price = $row["price"];
                }
            }

            $sql3 = "SELECT * FROM `vehicledetails` WHERE `vehicleID`='$cropID'";
            $result3 = $con->query($sql3);
            if ($result3->num_rows > 0) {
                foreach ($result3 as $row) {
                    $description = $row["description"];

                    $name = $row["name"];
                    $type = $row["type"];
                    $harvest_date = $row["harvest_date"];
                    $weight = $row["weight"];
                    $Region = $row["Region"];
                    $Season = $row["Season"];
                    $State = $row["State"];
                    $soil_type = $row["soil_type"];
                    $temperature = $row["temperature"];
                }
            }


            ?>


            <div class="col-md-6 col-sm-6 col-sm-12">
                <div class="shop-product-heading">
                    <h2><?= $modelname ?></h2>
                </div>
                <div class="shop-product-heading">
                    <h2><label style="color: #E34677;">Time Remain:</label> <span id="coundown"></span></h2>
                </div>
                <div class="col-md-6 col-sm-6 col-sm-12">
                    <div class="shop-product-heading">
                        <h4>Start Date: <?= $startDate ?></h4>
                    </div>
                    <div class="shop-product-heading">
                        <h4>End Date: <?= $EndDate ?></h4>
                    </div>


                    <p class="mb15"><?php $descriptionPart = substr($description, 0, 70);
                                    echo ($descriptionPart . ".....");
                                    ?> </p><br>

                    <ul class="list-inline shop-product-prices mb30" style="width: 330px;">
                        <li class="shop-red">
                            <h4>Base Price (RS):<label id="basePrice"> <?= $price ?></label> </h4>
                        </li>
                        <li class="shop-red">
                            <h4>Top Bid (Rs):<label id="topbidlevel"> </label></h4> <input type="hidden" id="hidenvalue" value="<?php if (empty($_SESSION["topbid"])) {
                                                                                                                                    echo (0);
                                                                                                                                } else {
                                                                                                                                    echo ($_SESSION["topbid"]);
                                                                                                                                } ?>">
                        </li>
                        <li>
                            <div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document" style="width: 100%">
                                    <div class="modal-content">
                                        <div class="modal-header text-center">
                                            <h4 class="modal-title w-100 font-weight-bold">Top Bidder</h4>
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Bidder Name</th>
                                                        <th>Date</th>
                                                        <th>Bid Price</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sql5 = "SELECT `user`.`name`, `bidder`.`biddingTime`,`bidder`.`price` FROM `bidder` INNER JOIN `user`ON bidder.vehicleID=user.ID WHERE `bidder`.`vehicleID`=$cropID ORDER BY `bidder`.`price` DESC";
                                                    $result3 = $con->query($sql5);
                                                    if ($result3->num_rows > 0) {
                                                        foreach ($result3 as $row) {

                                                    ?>
                                                            <tr>
                                                                <td><?= $row["name"] ?></td>
                                                                <td><?= $row["biddingTime"] ?></td>
                                                                <td><?= $row["price"] ?></td>
                                                            </tr>
                                                    <?php }
                                                    } ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--
<div class="text-center">
  <a href="" class="btn btn-default btn-rounded mb-4" data-toggle="modal" data-target="#modalLoginForm">Watch Top Bidder</a>
</div>  -->
                        </li>
                    </ul>

                    <div class="shop-product-heading">
                        <h4>Crop Type: <strong><?= $type ?></strong></h4>
                        <div>
                            <!--	<img src="../img/icon/icon-add.png" alt="add icon">
                              	<span><a id="watchlist" onClick="watchList(<? #=$cropID
                                                                                ?>)">Add to watch list</a></span> -->
                        </div>
                    </div>


                    <h3 class="">Bid price: </h3>
                    <div class="mb30">

                        <div class="alert alert-warning" id="bidAlert">
                            <strong>Warning! please input more than top bidder or base amount</strong>
                        </div>
                        <input type='number' class="form-control " placeholder="Input bid price " name='bidprice' value="" id='bid' required onKeyUp="bidCheck(<?= $topBid ?>)" <?php if (isset($_SESSION["role"])) { ?> disabled <?php } ?> />

                        <input style="margin-top: 5%;" class="btn btn-product" type="submit" value="Submit" name="submitBtn" id="btnSubmit" onClick="bidingQuery()" <?php if (isset($_SESSION["role"])) { ?> disabled <?php } ?>>


                    </div>


                </div>
            </div>
        </div>
</section>


<section class="spa-area spa-area-17">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="single-detail-product">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="widget-header style-08">
                                <span class="sub-title style-03">POSTED: <?= $startDate ?></span>
                                <h3 class="widget-title style-05"><?= $modelname ?></h3>
                            </div>
                            <!--
                                        <ul class="spa-meta-data-single">
                                            <li><a href="#"><i class="fa fa-map-marker"></i>India</a>
                                            </li>
                                            <li><a href="#"><i class="fa fa-feed"></i>ADVERTISEMENT ID : 240042945</a>
                                            </li>
                                            <li><a href="#"><i class="fa fa-bookmark-o"></i>PREMIUM</a>
                                            </li>
                                            
                                            <li><a href="#"><i class="fa fa-star"></i>ADD TO FAVOURITE</a>
                                            </li>
                                        </ul>
                                        -->

                            <div class="widget-header style-15">
                                <span class="sub-title style-03">ABOUT THIS CROP</span>
                                <h3 class="widget-title style-05">CROP SPECIFICATION</h3>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <td><i class="fa fa-caret-right"></i>Name</td>
                                                <td><?= $name ?></td>
                                            </tr>
                                            <tr>
                                                <td><i class="fa fa-caret-right"></i>Type</td>
                                                <td><?= $type ?></td>
                                            </tr>
                                            <tr>
                                                <td><i class="fa fa-caret-right"></i>Harvest Date</td>
                                                <td><?= $harvest_date ?></td>
                                            </tr>

                                            <tr>
                                                <td><i class="fa fa-caret-right"></i>Weight</td>
                                                <td><?= $weight ?></td>
                                            </tr>
                                            <tr>
                                                <td><i class="fa fa-caret-right"></i>Region</td>
                                                <td><?= $Region ?></td>
                                            </tr>



                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-striped">
                                        <tbody>

                                            <tr>
                                                <td><i class="fa fa-caret-right"></i>State</td>
                                                <td><?= $State ?></td>
                                            </tr>


                                            <tr>
                                                <td><i class="fa fa-caret-right"></i>Soil Type</td>
                                                <td><?= $soil_type ?></td>
                                            </tr>


                                            </tr>
                                            <tr>
                                                <td><i class="fa fa-caret-right"></i>Temperature</td>
                                                <td><?= $temperature ?></td>
                                            </tr>
                                            <tr>
                                                <td><i class="fa fa-caret-right"></i>Season</td>
                                                <td><?= $Season ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="widget-header style-15">
                                <span class="sub-title style-03">CROP DESCRIPTION</span>
                                <h3 class="widget-title style-05">CROP OVERVIEW</h3>
                            </div>
                            <p><?= $description ?></p>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<hr>
<div class="container">

    <div>
        <h3 style="background-color: darkgray; text-decoration: underline; "><span>Deposit & what it is </span></h3>
        <p>Deposit is a saving amount for Crop auction. if an auctioneer wouldn't take the Crop after completing the bidding then this amount will be cut down and action will be taken according to the law and company policy. If any actioneer lose the bid then this amount will be fixed to their account and it can be used for their next bidding.<br />
            <label>Bank information for deposit amount</label><br />
            <span>Bank: Nepal National Bank</span><br />
            <span>Account name: Samps</span><br />
            <span>Account No: 3578 7105 7378</span><br /><br />
            <span>After successful transaction, go to <label>make payment</label> option and confirm your transactions.</span><br />
            <span>To see your balance, go to <label>Account </label> section.</span><br>
        </p>
    </div>
    <div>
        <h3 style="background-color: darkgray; text-decoration: underline; "><span>Warranties and Disclaimer</span></h3>
        <p>Disclaimer: By bidding on any item, you expressly agree that use of the website and the services is at your sole risk and subject to the user agreement. The website, the services and any goods or services purchased or obtained through the website, the services or any transactions entered into through the website or services are provided on an “as is” and “as available” basis. PropertyRoom disclaims, on its own behalf and, when acting as an agent, on behalf of its principal, all warranties of any kind, whether express or implied, and specifically disclaims any implied warranties of title, merchantability, fitness for a particular purpose and non-infringement. No advice, opinions or information, whether oral or written, obtained from PropertyRoom or through the website or services shall create any warranty. Some jurisdictions do not allow the exclusion of certain warranties, so the some of the foregoing exclusions may not apply to you. This warranty gives you specific legal rights and you may also have other legal rights which vary from jurisdiction to jurisdiction</p>
    </div>
    <div>
        <h3 style="background-color: darkgray; text-decoration: none;"><span>ACCEPTANCE OF CONDITION - BUYER INSPECTION/PREVIEW</span></h3>
        <p>Crops and equipment often display significant wear and tear. Assets are sold AS IS with no warranty, express or implied, and we highly recommend previewing them before bidding. The preview period is the only opportunity to inspect an asset to verify condition and suitability. No refunds, adjustments or returns will be entertained.

            </br></br>Crop preview inspections of the Crop can be done at the below location on Monday and Tuesday from 10am - 2pm. Please call the yard ahead of time to ensure prompt service</p>
    </div>
</div>
<hr>
<!-- review vehicle -->
<section class="main-contain dip-style">
    <div class="container">
        <h1 style="text-decoration: underline;">Reviews</h1>

        <div id="reviewPlace">

        </div>
        <div class="form-group" style="margin-top: 30px;">
            <tr>
                <td><textarea id="review" name="comment" class="form-control" rows="3"></textarea></td>
                <td><button class="btn btn-primary" style="margin-top: 10px;" id="reviewSubmit">Submit</button></td>
            </tr>


        </div>
    </div>
</section>

<!-- related vehicle -->
<section class="main-contain dip-style">
    <div class="container">


        <div class="related-products-style-in-productp">
            <div class="widget">
                <h6 class="text-uppercase ">Related Crops</h6>
            </div>
            <div class="category-products col-grid-style">
                <div class="row clearfix">
                    <div class="row clearfix">

                        <?php
                        $sql = "SELECT vehicle.*, catagory.name as catname FROM `vehicle` INNER JOIN catagory ON catagory.ID=vehicle.catagory WHERE vehicle.`ID` != '$cropID'  AND vehicle.`type`='$type' AND vehicle.status=1";

                        $result = $con->query($sql);
                        if ($result->num_rows > 0) {
                            foreach ($result as $row) {
                                $ID = $row["ID"];
                                $name = $row["name"];
                                $type = $row["type"];
                                $catagory = $row["catname"];
                                $startDate_ = $row["startDate"];
                                $EndDate_ = $row["EndDate"];
                                $image = $row["image"];
                                $price = $row["price"];
                        ?>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="products grid-product mb30">
                                        <div class="product-grid-inner">
                                            <div class="product-grid-img">
                                                <ul class="product-labels">
                                                    <li><?php date_default_timezone_set("Asia/Kathmandu");

                                                        $nowDate = date("Y-m-d");

                                                        if (($nowDate > $EndDate_) || ($startDate_ > $EndDate_)) {
                                                            echo ("Bidding Expired");
                                                        } else {
                                                            echo ("Bidding Allowed");
                                                        }
                                                        ?></li>
                                                </ul>
                                                <a href="<?= $_SESSION["directory"] ?>farmer/cropdetail.php?id=<?= $ID ?>" class="product-grid-img-thumbnail">
                                                    <img style="width: 335px; height: 190px; " src="<?= $_SESSION["directory"] ?>img/vehicle/<?= $image ?>" alt="img" class="img-responsive primary-img" />
                                                    <img src="<?= $_SESSION["directory"] ?>img/vehicle/<?= $image ?>" alt="img" class="img-responsive socendary-img" />
                                                </a>

                                                <div class="product-grid-atb text-center">
                                                    <a class="" href="<?= $_SESSION["directory"] ?>farmer/cropdetail.php?id=<?= $ID ?>">View Details</a>
                                                </div>
                                            </div>

                                            <div class="product-grid-caption text-center">
                                                <h6><a class="p-grid-title" href="<?= $_SESSION["directory"] ?>farmer/cropdetail.php?id=<?= $ID ?>"><?= $name ?></a></h6>
                                                <h4>Base Price:Rs <strong><?= $price ?></strong></h4>
                                                <!--	<h5>Bid Price:<strong><?= $price ?></strong> Rs</h5> -->
                                                <div class="pro-size"><label>Type: <span><?= $type ?></span> </label>
                                                    <div><label>Category: <span><?= $catagory ?></span> </label></div>
                                                </div>

                                                <a class="btn btn-product cd-cart-btn" href="<?= $_SESSION["directory"] ?>farmer/cropdetail.php?id=<?= $ID ?>"> BID</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                            <?php
                            }
                        } else {

                            ?>
                            <div>
                                <h3> No Crops Available..</h3>
                            </div>
                        <?php
                        }


                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
include("../footer.php");
?>



<script type="text/javascript">
    function coundown() {
        var st = "<?php echo ($startDate); ?>";
        var end = "<?php echo ($EndDate); ?>";
        var stDate = new Date(st);
        var endDate = new Date(end);
        /*
        var eDate=evetDate.getDate();
		var eMonth=evetDate.getMonth();
        var eYear=evetDate.getFullYear();
        */

        var sDate = stDate.getDate();
        var sMonth = stDate.getMonth();
        var sYear = stDate.getFullYear();

        var eDate = endDate.getDate();
        var eMonth = endDate.getMonth();
        var eYear = endDate.getFullYear();

        //var countDownDate = new Date(eYear,eMonth,eDate,0,0,0).getTime();
        //var stcountDownDate = new Date(sYear,sMonth,sDate).getTime();

        var ecountDownDate = new Date(eYear, eMonth, eDate).getTime();

        var x = setInterval(function() {


            var now = new Date().getTime();


            //var distance = countDownDate - now;
            var distance = ecountDownDate - now;
            //var distance= endDate - stDate;    

            var day = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hour = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minute = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var second = Math.floor((distance % (1000 * 60)) / 1000);


            document.getElementById("coundown").innerHTML = (day + "d: " + hour + "h: " + minute + "m: " + second + "s ");


            if (distance < 0) {
                $("#bid").attr({
                    'readonly': true,
                    'placeholder': 'Disabled'
                });
                $("#btnSubmit").attr({
                    'value': 'Submit(Disabled)',
                    'disabled': "disabled"
                });

                clearInterval(x);
                document.getElementById("coundown").innerHTML = "EXPIRED";
            }
        }, 1000);

    }
    coundown();




    $("#bidAlert").hide();
    $("#bid").click(function() {
        $("#bidAlert").show();
    })


    function bidCheck(INR) {
        var check = $("#bid").val();
        if (check > INR) {
            window.setTimeout(function() {
                $("#bidAlert").fadeTo(500, 0).slideUp(500, function() {
                    $("#bidAlert").show();
                });
            }, 1500);
        } else {
            window.setTimeout(function() {
                $("#bidAlert").fadeTo(500, 0).slideUp(500, function() {
                    $("#bidAlert").show();
                });
            }, 1500);
        }
        // alert(c);
        //
    }

    function bidingQuery() {
        var GivenValue = $("#bid").val();
        var BaseValue = $("#basePrice").text();
        BaseValue = parseInt(BaseValue);
        var BidValue = parseInt($("#hidenvalue").val());
        var deAmount = parseInt(<?php echo ($d_amount); ?>);

        if (GivenValue == "") {
            alert("Please give bidding price");
        } else if ((BidValue >= GivenValue && GivenValue > 0) || (BaseValue >= GivenValue)) {
            alert("Please give more than top bidding/base amount.");
        } else if (deAmount < BaseValue) {
            alert("You have to deposit in your account more than base amount price, before you can start bidding. For more information kindly scroll down the page.");
        } else {
            window.location.href = "bid.php?id=<?= $cropID ?> && bidprice=" + GivenValue;
        }
    }


    function topBID() {
        var vid = <?= $cropID ?>;
        setInterval(function() {
            $.get('bid.php', {
                VID: vid
            }, function(data) {
                $("#topbidlevel").text(data);
            });

        }, 1000);
    }
    topBID();


    function watchList(id) {
        $.get('Addwatchlist.php', {
            VID: id
        }, function(data) {
            alert(data);
        });

    }

    $("#reviewSubmit").click(function() {
        var review = $("#review").val();
        var ID = <?php echo ($cropID); ?>;
        $.get('review.php', {
            review: review,
            vid: ID
        }, function(data) {
            //	alert(data);
        });
        $("#review").val("");
    })

    function checkReview() {
        var check = 1;
        var ID = <?php echo ($cropID); ?>;
        setInterval(function() {
            $.get('review.php', {
                check: check,
                ID: ID
            }, function(data) {
                $("#reviewPlace").html(data);
            });

        }, 1000);
    }
    checkReview();
</script>