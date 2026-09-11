
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel fundacion</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.css" integrity="sha512-x9WwyMYBnlXMNQ6kQ/Lyzu1NqIhLQKL5Oq6xByfXuRj7s9CskyCbLv/1IjqzJmXwFXWr0ov6jBV7Qbc0hh9nHg==" crossorigin="anonymous" referrerpolicy="no-referrer">

    <style>
        main{
            background-color: #F3F6F4; 
        }
    </style>
</head>
<body>
    <div class="logo">
        <i class="fa-solid fa-house"></i>
        <h2>Huellas felices</h2>
        <h3>fundacion</h3>
    </div>
    <header class="navBar">
        <button><i class="fa-solid fa-house"></i><span>panel</span></button>
        <button><i class="fa-solid fa-shield-dog"></i><span>mis animales </span></button>
        <button><i class="fa-regular fa-file"></i><span>solicitudes </span></button>
        <button><i class="fa-solid fa-plus"></i><span>registrar animal</span></button>
        <button><i class="fa-solid fa-circle-check"></i><span>verificacion</span></button>
        <button><i class="fa-solid fa-location-dot"></i><span>sedes</span></button>
        <button><i class="fa-solid fa-gear"></i><span>configuracion</span></button>
        
        <button><i class="fa-solid fa-arrow-right-from-bracket"></i><span>Cerrar sesión</span></button>
    </header>
    <main>
        <div class="cont-princ">
            <div class="panel">
                <div class="info">
                    <h1>Panel de fundación</h1>
                    <h3>Huellas Felices-bogota</h3>
                    <button><i class="fa-solid fa-plus"></i><span>registrar animal</span></button>
                </div>

                <div class="estado">
                       <i class="fa-solid fa-triangle-exclamation"></i>
                        <p>tienes un animal esperando a que actualices su estado</p>
                        <button><i class="fa-solid fa-arrow-right"></i><span>revisar ahora</span></button>
                </div>

                <div class="card-1">
                        <div class="logo-1">
                            <i class="fa-solid fa-paw"></i>
                        </div>
                        <p>2</p>
                        <p>animales disponibles</p>
                </div>
                <div class="card-2">
                    <div class="logo-2">
                          <i class="fa-regular fa-shield"></i>
                        </div>
                        <p>1</p>
                        <p>En espera</p>

                </div>
                <div class="card-3">
                    <div class="logo-3">
                         <i class="fa-regular fa-file"></i>
                        </div>
                        <p>5</p>
                        <p>Solicitudes sin revisar</p>

                </div>
                <div class="card-4">
                    <div class="logo-4">
                        <i class="fa-regular fa-heart"></i>
                        </div>
                        <p>3</p>
                        <p>Adopciones del mes </p>

                </div>

                <div class="solic">
                    <h2>ultimas solicitudes</h2>
                    <table>
                        <tr> <!-- fila-->
                            <th>ANIMAL</th>
                            <th>SOLICITANTE</th>
                            <th>FECHA</th>
                            <th>ESTADO</th>
                        </tr>

                        <tr>
                            <td>Zeus</td>
                            <td>Valentina Torres</td>
                            <td>19 ago</td>
                            <td>Nueva</td>
                        </tr>

                        <tr>
                            <td>Zeus</td>
                            <td>ricardo mejia</td>
                            <td>20 ago</td>
                            <td>pendiente</td>
                        </tr>

                        <tr>
                            <td>Tatu</td>
                            <td>Ana Mora</td>
                            <td>21 ago</td>
                            <td>Nueva</td>
                        </tr>

                        <tr>
                            <td>Cleo</td>
                            <td>Laura Herrera</td>
                            <td>21 ago</td>
                            <td>Rechazado</td>
                        </tr>

                        <tr>
                            <td>Cleo</td>
                            <td>Felipe Suarez </td>
                            <td>21 ago</td>
                            <td>Adoptado</td>
                        </tr>
                    </table>
                </div>

                <div class="animals">
                    <h2>mis animales</h2>
                    <button class="animals-1">
                                <img src="{{ asset('storage/img/animales/perro1.jpg') }}" alt="Perro">
                    </button>
                    
                    <button class="animals-2">
                                <img src="" alt="">
                    </button>

                    <button class="animals-3">
                                <img src="" alt="">
                    </button>

                    <button class="animals-4">
                                <img src="" alt="">

                    </button>
                </div>


            
            </div>

        </div>


 
    </main>
</body>
</html>