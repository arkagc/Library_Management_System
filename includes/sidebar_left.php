<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <div class="brand-link">
    <img src="images/admin_logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">LMS</span>
  </div>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <?php
          if($_SESSION['UserType']!='admin')
          {
              $sql_users  = mysqli_query($con,"SELECT * FROM `users` WHERE user_id='".$_SESSION['UserId']."'");
              $fetch_users= mysqli_fetch_array($sql_users);
              if(!empty($fetch_users['user_image']))
              {
        ?>
                <img src="uploads/student/<?php echo $fetch_users['user_image'];?>" class="img-circle elevation-2" alt="User Image">
        <?php
              }
              else
              {
        ?>
                <img src="uploads/student/icon.png" class="img-circle elevation-2" alt="User Image">
        <?php
              }
          }
          else
          {
        ?>
            <img src="images/admin_logo.png" class="img-circle elevation-2" alt="User Image">
        <?php
          }
        ?>
      </div>
      <div class="info">
        <h6 style="color:#ffffff;">
          <?php
            if($_SESSION['UserType']!='admin')
            {
              $sql_users  = mysqli_query($con,"SELECT * FROM `users` WHERE user_id='".$_SESSION['UserId']."'");
              $fetch_users= mysqli_fetch_array($sql_users);

              echo strtok(ucwords($fetch_users['user_name']), " ");
            ?>
              <!-- Student -->
            <?php
            }
            else
            {
            ?>
              Admin
            <?php
            }
            ?>
        </h6>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Admin Dashboard / Student Profile Menu -->
        <li class="nav-item">
          <?php
            if($_SESSION['UserType']=='admin')
            {
          ?>
              <a href="dashboard.php" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Dashboard
                </p>
              </a>
          <?php
            }
            else
            {
          ?>
              <a href="student_dashboard.php" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Dashboard
                </p>
              </a>
          <?php
            }
          ?>
        </li>

        <!-- Student Profile Menu -->
        <li class="nav-item">
          <?php
            if($_SESSION['UserType']=='student')
            {
          ?>
              <a href="student_profile.php" class="nav-link">
                <i class="nav-icon fas fa-user"></i>
                <p>
                  My Profile
                </p>
              </a>
          <?php
            }
          ?>
        </li>

        <!-- Department Menu -->
        <li class="nav-item">
          <?php
            if($_SESSION['UserType']=='admin')
            {
          ?>
              <a href="all_department.php" class="nav-link">
                <i class="nav-icon fas fa-building"></i>
                <p>
                  Department
                </p>
              </a>
          <?php
            }
          ?>
        </li>

        <!-- Department Year Menu -->
        <li class="nav-item">
          <?php
            if($_SESSION['UserType']=='admin')
            {
          ?>
              <a href="all_department_year.php" class="nav-link">
                <i class="nav-icon fas fa-calendar-alt"></i>
                <p>
                  Department Year
                </p>
              </a>
          <?php
            }
          ?>
        </li>

        <!-- Members Menu -->
        <li class="nav-item">
          <?php
            if($_SESSION['UserType']=='admin')
            {
          ?>
              <a href="all_member.php" class="nav-link">
                <i class="nav-icon fas fa-users"></i>
                <p>
                  Members
                </p>
              </a>
          <?php
            }
          ?>
        </li>

        <!-- books Category Menu -->
        <li class="nav-item">
          <?php
            if($_SESSION['UserType']=='admin')
            {
          ?>
              <a href="all_books_category.php" class="nav-link">
                <i class="nav-icon fas fa-list"></i>
                <p>
                  Books Category
                </p>
              </a>
          <?php
            }
          ?>
        </li>

        <!-- Books Menu -->
        <li class="nav-item">
          <?php
            if($_SESSION['UserType']=='admin' || $_SESSION['UserType']=='student')
            {
          ?>
              <a href="all_books_list.php" class="nav-link">
                <i class="nav-icon fas fa-book"></i>
                <p>
                  Books
                </p>
              </a>
          <?php
            }
          ?>
        </li>

        <!-- Magazines Category Menu -->
        <li class="nav-item">
          <?php
            if($_SESSION['UserType']=='admin')
            {
          ?>
              <a href="all_magazines_category.php" class="nav-link">
                <i class="nav-icon fas fa-list"></i>
                <p>
                  Magazines Category
                </p>
              </a>
          <?php
            }
          ?>
        </li>

        <!-- Magazines Menu -->
        <li class="nav-item">
          <?php
            if($_SESSION['UserType']=='admin' || $_SESSION['UserType']=='student')
            {
          ?>
              <a href="all_magazines_list.php" class="nav-link">
                <i class="nav-icon fas fa-book-open"></i>
                <p>
                  Magazines
                </p>
              </a>
          <?php
            }
          ?>
        </li>

        <!-- Newspaper Category Menu -->
        <li class="nav-item">
          <?php
            if($_SESSION['UserType']=='admin')
            {
          ?>
              <a href="all_newspapers_category.php" class="nav-link">
                <i class="nav-icon fas fa-list"></i>
                <p>
                  News Papers Category
                </p>
              </a>
          <?php
            }
          ?>
        </li>

        <!-- newspaper Menu -->
        <li class="nav-item">
          <?php
            if($_SESSION['UserType']=='admin' || $_SESSION['UserType']=='student')
            {
          ?>
              <a href="all_newspapers_list.php" class="nav-link">
                <i class="nav-icon fas fa-newspaper"></i>
                <p>
                  News Papers
                </p>
              </a>
          <?php
            }
          ?>
        </li>

        <!-- Issue Menu -->
        <li class="nav-item">
          <?php
            if($_SESSION['UserType']=='admin' || $_SESSION['UserType']=='student')
            {
          ?>
            <a href="#" class="nav-link">
              <i class="nav-icon  fas fa-outdent"></i>
              <p>
                Issue
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="all_books_issue_list.php" class="nav-link">
                  <i class="fas fa-book nav-icon"></i>
                  <p>Books</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="all_magazines_issue_list.php" class="nav-link">
                  <i class="fas fa-book-open nav-icon"></i>
                  <p>Magazines</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="all_newspapers_issue_list.php" class="nav-link">
                  <i class="fas fa-newspaper nav-icon"></i>
                  <p>Newspapers</p>
                </a>
              </li>
            </ul>
          <?php
            }
          ?>
        </li>

        <!-- Returned Menu -->
        <li class="nav-item">
          <?php
            if($_SESSION['UserType']=='admin' || $_SESSION['UserType']=='student')
            {
          ?>
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-thumbs-up"></i>
                <p>
                  Return
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="all_books_return_list.php" class="nav-link">
                    <i class="fas fa-book nav-icon"></i>
                    <p>Books</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="all_magazines_return_list.php" class="nav-link">
                    <i class="fas fa-book-open nav-icon"></i>
                    <p>Magazines</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="all_newspapers_return_list.php" class="nav-link">
                    <i class="fas fa-newspaper nav-icon"></i>
                    <p>Newspapers</p>
                  </a>
                </li>
              </ul>
          <?php
            }
          ?>
        </li>
        <!-- Not Returned Menu -->
        <li class="nav-item">
          <?php
            if($_SESSION['UserType']=='admin' || $_SESSION['UserType']=='student')
            {
          ?>
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-thumbs-down"></i>
                <p>
                  Not Returned
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="all_books_not_return_list.php" class="nav-link">
                    <i class="fas fa-book nav-icon"></i>
                    <p>Books</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="all_magazines_not_return_list.php" class="nav-link">
                    <i class="fas fa-book-open nav-icon"></i>
                    <p>Magazines</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="all_newspapers_not_return_list.php" class="nav-link">
                    <i class="fas fa-newspaper nav-icon"></i>
                    <p>Newspapers</p>
                  </a>
                </li>
              </ul>
          <?php
            }
          ?>
        </li>

        <!-- Fine Chart Menu -->
        <li class="nav-item">
          <?php
            if($_SESSION['UserType']=='admin' || $_SESSION['UserType']=='student')
            {
          ?>
              <a href="all_fine_list.php" class="nav-link">
                <i class="nav-icon fas fa-money-bill"></i>
                <p>
                  Fine Details
                </p>
              </a>
          <?php
            }
          ?>
        </li>

          <!-- Log out Menu -->
        <li class="nav-item">
          <a href="logout.php" class="nav-link">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p>
              Log out
            </p>
          </a>
        </li>

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>