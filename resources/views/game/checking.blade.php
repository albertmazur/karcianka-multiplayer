<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

</body>
@vite('resources/js/app.js')
<script>
    setTimeout(() =>{
        window.Echo.channel('testing')
        .listen('.MyWebSocket', (e)=>{
            console.log(e)
        })
    }, 200)
</script>
</html>
