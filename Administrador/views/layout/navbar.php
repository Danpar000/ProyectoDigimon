<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
      <div class="position-sticky pt-3">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link active disabled" aria-current="page" href="#">
              <span data-feather="home"></span>
              Dashboard
            </a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="index.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="far fa-user"></i> Usuarios</a>
            <ul class="dropdown-menu dropdown-menu-dark">
              <li><a class="dropdown-item" href="index.php?tabla=user&accion=crear">Añadir</a></li>
              <!-- <li><a class="dropdown-item" href="index.php?tabla=user&accion=listar">Listar </a></li> -->
              <li><a class="dropdown-item" href="index.php?tabla=user&accion=buscar">Buscar </a></li>
          </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="index.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user-tie"></i> Digimons</a>
            <ul class="dropdown-menu dropdown-menu-dark">
              <li><a class="dropdown-item" href="index.php?tabla=digimons&accion=crear">Añadir</a></li>
              <!-- <li><a class="dropdown-item" href="index.php?tabla=digimons&accion=listar">Listar </a></li> -->
              <li><a class="dropdown-item" href="index.php?tabla=digimons&accion=buscar">Buscar </a></li>
          </ul>
          </li>
        </ul>
      </div>
    </nav>