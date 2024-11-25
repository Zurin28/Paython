<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <title>PAYTHON</title>
  <link rel="stylesheet" href="styles.css">
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php require_once '../admin/Adminsidebar.php';
      require_once '../admin/Admintopbar.php';?>

  <script>
    const sidebar = document.querySelector('.sidebar');
    const sidebarBtn = document.querySelector('.sidebarBtn');

    sidebarBtn.addEventListener('click', () => {
      sidebar.classList.toggle('active'); 
    });
  </script>
</body>
</html>
