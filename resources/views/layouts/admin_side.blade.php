<ul class="nav nav-pills nav-stacked nav-bracket">
        @foreach($menu_list as $menu)
        @if($menu_group == $menu['group'] or $menu['group'] == $current_route)
        <li class="nav-parent nav-active active" data-name="{{ $menu_name }}">
        @else
        <li class="nav-parent">
        @endif
          <a href="#"><i class="fa fa-th-list"></i> <span>{{$menu['name']}}</span>
           <!--  <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span> -->
          </a>
          @foreach($menu['sub_menu'] as $sub_menu)
          <ul class="children" @if($menu_group == $menu['group'] or $menu['group'] == $current_route) style="display: block;"@endif>
            @if($request_path == $sub_menu['link'])
            <li class="active"><a href="{{$sub_menu['link']}}">{{$sub_menu['name']}}</a></li>
            @else
            <li><a href="{{$sub_menu['link']}}">{{$sub_menu['name']}}</a></li>
            @endif
            <!--li><a href="#">Link in level 2</a></li-->
          </ul>
          @endforeach
        </li>
        @endforeach
</ul>