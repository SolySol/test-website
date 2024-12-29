<?php
// Success messages
if(isset($success_msg)){
   foreach($success_msg as $msg){
      echo '<script>
         swal({
            title: "Success!",
            text: "'.$msg.'",
            icon: "success",
            button: "OK",
            timer: 3000,
            className: "swal-success"
         });
      </script>';
   }
}

// Warning messages
if(isset($warning_msg)){
   foreach($warning_msg as $msg){
      echo '<script>
         swal({
            title: "Warning!",
            text: "'.$msg.'",
            icon: "warning",
            button: "Got it",
            timer: 3000,
            className: "swal-warning"
         });
      </script>';
   }
}

// Info messages
if(isset($info_msg)){
   foreach($info_msg as $msg){
      echo '<script>
         swal({
            title: "Information!",
            text: "'.$msg.'",
            icon: "info",
            button: "OK",
            timer: 3000,
            className: "swal-info"
         });
      </script>';
   }
}

// Error messages
if(isset($error_msg)){
   foreach($error_msg as $msg){
      echo '<script>
         swal({
            title: "Error!",
            text: "'.$msg.'",
            icon: "error",
            button: "Try Again",
            timer: 3000,
            className: "swal-error"
         });
      </script>';
   }
}
?>
