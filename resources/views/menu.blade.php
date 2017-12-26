<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<style>
ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #333;
}

li {
    float: left;
}

li a, .dropbtn {
    display: inline-block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}

li a:hover, .dropdown:hover .dropbtn {
    background-color: red;
}

li.dropdown {
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
}

.dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    text-align: left;
}

.dropdown-content a:hover {background-color: #f1f1f1}

.dropdown:hover .dropdown-content {
    display: block;
}
</style>


</head>
<body>
{{$menus}}
<ul>
  <li><a href="#home">Home</a></li>
  <li><a href="#news">News</a></li>
  <li class="dropdown">
    <a href="javascript:void(0)" class="dropbtn">Dropdown</a>
    <div class="dropdown-content">
      <a href="#">Link 1</a>
      <a href="#">Link 2</a>
      <a href="#">Link 3</a>
    </div>
  </li>
</ul>

<hr>

<ul>
@foreach($menus as $menu)
    @if($menu->parent_id == 0)
        <li><a href="#">{{ $menu->menu_name }}</a></li>
    @else
        <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn">Dropdown</a>
            <div class="dropdown-content">
                <a href="#">Link 1</a>
            </div>
        </li>
    @endif
@endforeach
</ul>

<hr>
<ul id="m"></ul>


<script type="text/javascript">
    var menuData = <?php echo json_encode($menus); ?>;
    function mainMenu(menus, $id){
        var a = "", b = "";
        menus.forEach(function (menu) {
            if(menu.parent_id == 0 ){
                a +='<li><a href="#'+menu.menu_name+'">'+menu.menu_name+'</a></li>';
            }else{
                //a +='<li class="dropdown"><a href="javascript:void(0)" class="dropbtn">'+menu.paid+' Dropdown</a><div class="dropdown-content"><a href="#'+menu.menu_name+'">'+menu.menu_name+'</a></div></li>';
                b += '<a href="#'+menu.menu_name+'">'+mainMenu(menus, menu.parent_id)+'</a>';
            }
        });
        a +='<li class="dropdown">';
        a +='<a href="javascript:void(0)" class="dropbtn">Dropdown</a>';
        a +='<div class="dropdown-content">'+b+'</div></li>';
        
        return a;
    }
    var mainmenu = mainMenu(menuData, 0);
    $('#m').html(mainmenu);
</script>
</body>
</html>