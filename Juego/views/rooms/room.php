<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-around flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h3">Sala - conIDaSaber</h1>
        <button class="btn btn-danger mx-2">Crear sala</button>
        <h1 class="h3"> | </h1>
        <form action="index.php?tabla=digimons&accion=buscar&evento=filtrar" method="POST" class="d-flex">
            <div class="input-group">
                <input type="text" required class="form-control" id="busqueda" name="busqueda" placeholder="Buscar ID">
                <button type="submit" class="btn btn-success"><i class="fas fa-search"></i> Buscar</button>
            </div>
        </form>
    </div>
    <div id="contenido">
<!--        <div class="--><?php //= $clase ?><!--" --><?php //= $visibilidad ?><!-- role="alert">-->
<!--            --><?php //= $mensaje ?>
<!--        </div>-->
        <table class="table table-light table-hover">
            <thead class="table-dark">
            <tr>
                <th scope="col">ID Sala</th>
                <th scope="col">Integrantes</th>
                <th scope="col">Estado</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
                <!-- ACA LUEGO VA LA PARTE DE GENERAR EL JV CON LAS SALAS -->
                 <tr>
                    <td>Aca va el id</td>
                    <td>
                        <p>(1) 12312312301231231230123123123012312312301231231230</p>
                        <p>(2) 12312312301231231230123123123012312312301231231230</p>
                    </td>
                    <td>En juego</td>
                    <td><a href="index.php?tabla=digimons&accion=buscar&evento=filtrar"><button>Unirse</button></a></td>    
                 </tr>
            </tbody>
        </table>
    </div>
</main>