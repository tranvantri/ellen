<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="_token" content="{{ csrf_token() }}">
	@yield('title')
	
	@include('user.libraries.cssCode')
	
</head>
<body>

<!-- Menutop -->
	@include('user.core.menutop')
	<div class="nen-xam"></div>

	<!-- 2 nút sign in và sign up -->
	@include('user.core.loginUser')

	<!-- model hiển thị danh sách các sản phẩm trong giỏ hàng -->
	@include('user.core.modelCart')
	<!-- endMenutop -->
	<div class="clearfix"></div>

	@yield('content')
	<div id="push-notifi-promotion">
		@foreach($promotion as $prom)
			<div class="wrap-toast">
				<div class="toast-header">Khuyến mãi:</div>
				<div class="toast-content" style="height: 2rem;margin-bottom: 2px;" >{{$prom->name}} - Giảm Đến {{$prom->per_decr}}%</div>
				<div class="toast-footer">
					<i style="margin-right:unset;" class="fa fa-clock-o" aria-hidden="true"></i>
					<span class="end-time-text">Còn</span>
					<span class="end-time-timer2" data-time="{{$prom->end_date_sale}}"></span>
				</div>
			</div>
		@endforeach
	</div>
	<div class="clearfix"></div>
	@include('user.core.footer')
	
	@include('user.core.backToTop')

	@include('user.core.inputSearch')

	@include('user.libraries.jsCode')

	@yield('script')




</body>
</html>