@extends('layouts.navbar-only')

@section('content')
	<!-- Header -->
	<header class="masthead text-white text-center">
		<div class="overlay"></div>
		<div class="container">
			<div class="row">
				<div class="col-xl-9 mx-auto">
					<h1 class="big-text-h1">See the benefits of Premium!</h1>
				</div>
				<div class="col-md-10 col-lg-8 col-xl-7 mx-auto">
					<div class="col-12 col-md-4 mx-auto">
						<a role="button" class="btn btn-block btn-lg btn-dark" href="/payment">Buy now!</a>
					</div>
				</div>
			</div>
		</div>
	</header>

	<!-- Icons Grid -->
	<section class="welcome-bg-change features-icons text-center">
		<div class="container">
			<div class="row">
				<div class="col-lg-4">
					<div class="features-icons-item mx-auto mb-5 mb-lg-0 mb-lg-3">
						<div class="features-icons-icon">
							<i class="fas fa-expand-arrows-alt"></i>
						</div>

						<h3>Expand your abilities</h3>
						<p class="lead mb-0">Use all bundles for no additional cost</p>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="features-icons-item mx-auto mb-5 mb-lg-0 mb-lg-3">
						<div class="features-icons-icon d-flex justify-content-center">
							<i class="fas fa-paint-brush"></i>
						</div>

						<h3>Customize your app</h3>
						<p class="lead mb-0">Use only bundles you need or like</p>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="features-icons-item mx-auto mb-5 mb-lg-0 mb-lg-3">
						<div class="features-icons-icon d-flex justify-content-center">
							<i class="fab fa-wikipedia-w"></i>
						</div>

						<h3>Help Wikipedia</h3>
						<p class="lead mb-0">10% of our earned money is donated towards Wikimedia Foundation</p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Image Showcases -->
	<section class="showcase">
		<div class="container-fluid p-0">
			<div class="row g-0">
				<div class="col-lg-6 order-lg-2 text-white showcase-img" style="background-image: url('/storage/premium/Galaxy.jpg');"></div>
				<div class="col-lg-6 order-lg-1 my-auto showcase-text">
					<h2>Unlimited bundles</h2>
					<p class="lead mb-0">
						Use all available bundles for no additional cost! It's up to you which bundles you want to use. Make your experience better by choosing Premium!
					</p>
				</div>
			</div>

			<div class="row g-0">
				<div class="col-lg-6 text-white showcase-img" style="background-image: url('/storage/premium/Handshake.jpg');"></div>
				<div class="col-lg-6 my-auto showcase-text">
					<h2>Request own solutions</h2>
					<p class="lead mb-0">
						Premium users have the ability to request new bundles to be added. Need a way to manage your own specific thing? I got you covered!
					</p>
				</div>
			</div>

			<div class="row g-0">
				<div class="col-lg-6 order-lg-2 text-white showcase-img" style="background-image: url('/storage/premium/Charity.jpg'); background-position-y: bottom;"></div>
				<div class="col-lg-6 order-lg-1 my-auto showcase-text">
					<h2>Support charity</h2>
					<p class="lead mb-0">
						10% of all earned money goes straight to Wikimedia Foundation, which is a non-profit organization that maintains Wikipedia.
					</p>
				</div>
			</div>
		</div>
	</section>

	<!-- Call to Action -->
	<section class="call-to-action text-white text-center">
		<div class="overlay"></div>
			<div class="container">
				<div class="row">
					<div class="col-xl-9 mx-auto">
						<h1 class="big-text-h1">What are you waiting for?</h1>
					</div>
					<div class="col-md-10 col-lg-8 col-xl-7 mx-auto">
						<div class="col-12 col-md-4 mx-auto">
							<a role="button" class="btn btn-block btn-lg btn-dark" href="/payment">Buy now!</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection
