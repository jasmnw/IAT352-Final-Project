<!--------------------------------- art detail page part -------------------------------->
<?php include('private/initialize.php');
include("private/database/db.php");
include('private/controller/user.php');
?>

<?php
$table1 = 'artwork';
$table2 = 'artist';
// ---------- show one artwork detail ---------- //
if (isset($_GET['id'])) { // ---> if there is an exist id.
    $id = $_GET['id'];

    $art = selectOne($table1, $table2, ['artID' => $id]);
    $id = $art['artID'];
    $title = $art['artName'];
    $status = $art['status'];
    $siteName = $art['siteName'];
    $meterial = $art['material'];
    $photoURL = $art['photoURL'];
    $neighborhood = $art['neighborhood'];
    $year = $art['year'];
    $firstName = $art['firstName'];
    $lastName = $art['lastName'];
    $country = $art['country'];
    $type = $art['type'];
    $siteAddress = $art['siteAddress'];
    $description = $art['description'];
}

if (isset($_SESSION['userID'])) {
    $user_id = $_SESSION['userID'];
}

?>

<!-- html part -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Art - <?php echo $title ?></title>

    <!--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>-->
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
    <script src="http://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    <!--    <script src="https://raw.githubusercontent.com/furf/jquery-ui-touch-punch/master/jquery.ui.touch-punch.js"></script>-->

    <!-- for bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <!-- Custom Styling -->
    <style>
        <?php include(PUBLIC_PATH . '/Assets/css/css.css'); ?>#comment-container {
            height: 500px;
            margin-top: 50px;
        }

        textarea {
            width: 100%;
        }

        .post-btn {
            float: right;
        }

        .container .comment-box {
            width: 100%;
            display: flex;
            margin-top: 20px;
            margin-bottom: 30px;

        }

        .container .display-comment-box .display-comment {
            width: 100%;
            display: flex;
            margin-top: 20px;
            margin-bottom: 30px;
        }

        .container .display-comment-box .display-comment .profile img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
        }

        .container .display-comment-box .display-comment .comment-body {
            margin-left: 20px;
        }

        .container .display-comment-box .display-comment .comment-body .name-and-time {
            display: flex;
        }
    </style>
</head>

<body>
    <!-- header part -->
    <?php include(INCLUDE_PATH . '/header.php'); ?>
    <!-- end header part -->

    <!-- <form action="art_detail.php" method="post"> -->
    <input type="hidden" name="id" class="artID" value="<?php echo $id ?>">
    <div class="container">
        <?php if ($photoURL != '') {
            $photo = $photoURL;
        } else {
            $photo = 'public/Assets/img/1.jpg';
        }
        ?>
        <img src="<?php echo $photo ?>" alt="">
        <div class="text-container">
            <h1><?php echo $title ?></h1>
            <h4><?php echo $firstName ?> <?php echo $lastName ?></h4>
            <h5><?php echo $year ?></h5>
            <h5><?php echo $country ?></h5>
            <h5><?php echo $type ?></h5>
            <h5><?php echo $meterial ?></h5>
            <h5><?php echo $status ?></h5>
            <h5><?php echo $year ?></h5>
            <h4><?php echo $siteName ?></h4>
            <h5> <?php echo $siteAddress ?> <span> <?php echo $neighborhood ?> </span></h5>
            <p><?php echo $description ?></p>
        </div>
    </div>
    <!-- </form> -->

    <!-- for the comment body part -->
    <div class="container" id="comment-container">
        <h4><span class="count-of-comment"></span> Comments</h4>
        <span id="error_status"></span>
        <div class="comment-box">
            <textarea name="comment" id="comment" cols="100" rows="10" placeholder="Add your comment here..."></textarea>
        </div>
        <button class="post-btn">POST</button>

        <!-- displaying comment container part -->
        <div class="display-comment-box">

        </div>
        <!-- end displaying comment container part -->

    </div>
    <!-- end for the comment body part  -->

    <!-- jquery code part -->
    <script>
        var artID = $('.artID').val();
        load_comment(); // ---> run the function.
        // for loading the comment and display part

        function load_comment() {
            var artID = $('.artID').val();
            $.post("comment/comment.php", {
                artID: artID,
                comment_load_data: true
            }, function(res) {
                res = JSON.parse(res)
                console.log(res);
                if (typeof res === 'string') {
                    $('.display-comment-box').html("");
                    $('.display-comment-box').append('<p>' + res + '</p>');
                }
                if (typeof res != 'string') {
                    count = 0;
                    $('.display-comment-box').html("");
                    $.each(res, function(key, value) {

                        $('.display-comment-box').append('<div class="display-comment">\
                            <div class="profile">\
                                <img src="public/Assets/img/1.jpg" alt="">\
                            </div>\
                            <div class="comment-body">\
                                <div class="name-and-time">\
                                    <a href="">\
                                        <p style="font-weight:bold">' + value.user['username'] + '</p>\
                                    </a>\
                                    <p class="time" style="margin-left:5px; ">' + value.cmt['date'] + '</p>\
                                </div>\
                                <div class="comment-content">\
                                    <p>' + value.cmt['commentText'] + '</p>\
                                </div>\
                            </div>\
                        </div>\
                    ');
                        count++;
                    });

                    //show the number of comment 
                    $('.count-of-comment').html(count);
                }

            });
        }

        // for the post click event part
        $('.post-btn').click(function(e) {
            e.preventDefault();

            var msg = $('#comment').val();
            var artID = $('.artID').val();
            if ($.trim(msg).length == 0) {
                error_msg = '(Please type comment!!)';
                $('#error_status').html('<p>' + error_msg + '</p>')
            } else {
                error_msg = '';
                $('#error_status').html("")
            }
            if (error_msg != '') {
                return false;
            } else {

                var data = {
                    msg: msg,
                    artID: artID,
                    add_comment: true,
                };
                $.ajax({
                    type: "POST",
                    url: "comment/comment.php",
                    data: data,
                    success: function(responese) {
                        $('#comment').val("");
                        load_comment()

                    }

                });
            }

        })
    </script>
</body>

</html>