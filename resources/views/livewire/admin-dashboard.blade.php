
	<!-- start: MAIN -->
	<section >
		<!-- start: MAIN TOP -->
		<div class="main__top">
			<div class="main__top__title">
				<h3>Dashboard</h3>
				<ul class="breadcrumbs">
					<li><a href="#">Home</a></li>
					<li class="divider">/</li>
					<li><a href="#" class="active">Dashboard</a></li>
				</ul>
			</div>
			<ul class="main__top__menu">
				<!-- <li class="search">
					<a href="#">
						<i class="ph-magnifying-glass"></i>
					</a>
					<div class="main__dropdown">
						<form action="#">
							<input type="text" name="" placeholder="Search">
						</form>
						<span>Recent Search</span>
						<ul class="recent-search">
							<li>
								<a href="#">
									<h5>Keyword</h5>
									<p>Lorem ipsum dolor sit amet consectetur...</p>
								</a>
							</li>
							<li>
								<a href="#">
									<h5>Keyword</h5>
									<p>Lorem ipsum dolor sit amet consectetur...</p>
								</a>
							</li>
							<li>
								<a href="#">
									<h5>Keyword</h5>
									<p>Lorem ipsum dolor sit amet consectetur...</p>
								</a>
							</li>
							<li>
								<a href="#">
									<h5>Keyword</h5>
									<p>Lorem ipsum dolor sit amet consectetur...</p>
								</a>
							</li>
							<li>
								<a href="#">
									<h5>Keyword</h5>
									<p>Lorem ipsum dolor sit amet consectetur...</p>
								</a>
							</li>
							<li>
								<a href="#">
									<h5>Keyword</h5>
									<p>Lorem ipsum dolor sit amet consectetur...</p>
								</a>
							</li>
							<li>
								<a href="#">
									<h5>Keyword</h5>
									<p>Lorem ipsum dolor sit amet consectetur...</p>
								</a>
							</li>
						</ul>
					</div>
				</li> -->
				<li class="notification">
					<a href="#">
						<i class="ph-bell"></i>
					</a>
					<div class="main__dropdown">
						<div class="notification__top">
							<h4>Notifications</h4>
							<span>6</span>
						</div>
						<ul class="notification__item">
							<li>
								<a href="#">
									<div class="left">
										<h5>Notification title</h5>
										<p>Lorem ipsum dolor sit amet...</p>
									</div>
									<span>3 hours</span>
								</a>
							</li>
							<li>
								<a href="#">
									<div class="left">
										<h5>Notification title</h5>
										<p>Lorem ipsum dolor sit amet...</p>
									</div>
									<span>3 hours</span>
								</a>
							</li>
							<li>
								<a href="#">
									<div class="left">
										<h5>Notification title</h5>
										<p>Lorem ipsum dolor sit amet...</p>
									</div>
									<span>3 hours</span>
								</a>
							</li>
							<li>
								<a href="#">
									<div class="left">
										<h5>Notification title</h5>
										<p>Lorem ipsum dolor sit amet...</p>
									</div>
									<span>3 hours</span>
								</a>
							</li>
							<li>
								<a href="#">
									<div class="left">
										<h5>Notification title</h5>
										<p>Lorem ipsum dolor sit amet...</p>
									</div>
									<span>3 hours</span>
								</a>
							</li>
						</ul>
					</div>
				</li>
				<li class="inbox">
					<a href="#">
						<i class="ph-chat-circle-dots"></i>
					</a>
					<span></span>
				</li>
				<li class="profile">
					<a href="#">
                        <img class="clientImg" src="https://xsilica.com/images/xsilica_broucher_final_modified_05082016-2.png">
						<img src="https://images.unsplash.com/photo-1564564321837-a57b7070ac4f?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8Mnx8bWFufGVufDB8fDB8fA%3D%3D&auto=format&fit=crop&w=500&q=60" alt="">
					</a>
					<div class="main__dropdown">
						<div class="profile__top">
							<img src="https://images.unsplash.com/photo-1564564321837-a57b7070ac4f?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8Mnx8bWFufGVufDB8fDB8fA%3D%3D&auto=format&fit=crop&w=500&q=60" alt="">
							<div class="name">
								<h5>John Doe</h5>
								<p>Web Developer</p>
							</div>
						</div>
						<ul class="profile__menu">
							<li><a href="#"><i class="ph-user-circle-fill"></i> Edit profile</a></li>
							<li><a href="#"><i class="ph-gear-fill"></i> Settings</a></li>
						</ul>
					</div>
				</li>
			</ul>
		</div>
		<!-- end: MAIN TOP -->

		<!-- start: MAIN BODY -->
		<div class="main__body">

            <section class="tab-section">
                <div class="container-fluid">
                    <div class="tab-pane">
                        <button
                            type="button"
                            data-tab-pane="active"
                            class="tab-pane-item active"
                            onclick="tabToggle()"
                        >
                            <span class="tab-pane-item-title">01</span>
                            <span class="tab-pane-item-subtitle">Active</span>
                        </button>
                        <button
                            type="button"
                            data-tab-pane="in-review"
                            class="tab-pane-item after"
                            onclick="tabToggle()"
                        >
                            <span class="tab-pane-item-title">02</span>
                            <span class="tab-pane-item-subtitle">In Review</span>
                        </button>
                        <!-- <button
                            type="button"
                            data-tab-pane="pending"
                            class="tab-pane-item after"
                        >
                            <span class="tab-pane-item-title">03</span>
                            <span class="tab-pane-item-subtitle">Pending</span>
                        </button>
                        <button
                            type="button"
                            data-tab-pane="paused"
                            class="tab-pane-item after"
                        >
                            <span class="tab-pane-item-title">04</span>
                            <span class="tab-pane-item-subtitle">Paused</span>
                        </button> -->
                    </div>
                    <div class="tab-page active" data-tab-page="active">
                        <h1 class="tab-page-title">Active</h1>
                        <p class="tab-page-text">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            Incidunt ipsum totam vero deleniti? Facere?
                        </p>
                        <img
                            src="https://github.com/shadcn.png"
                            alt=""
                            class="tab-page-image"
                        />
                        <p class="tab-page-text">
                            Lorem ipsum dolor sit amet, consectetur adipisicing
                            elit. Excepturi deleniti, culpa eum ratione similique
                            est magni magnam vitae beatae accusantium, modi,
                            accusamus quisquam! Quos.
                        </p>
                        <p class="tab-page-text">
                            Lorem ipsum dolor sit, amet consectetur adipisicing
                            elit. Quos laboriosam earum maiores architecto obcaecati
                            eligendi, atque accusamus sed dolores beatae ea minima
                            sint itaque soluta doloribus neque quasi id ratione
                            saepe amet.
                        </p>
                    </div>
                    <div class="tab-page" data-tab-page="in-review">
                        <h1 class="tab-page-title">In Review</h1>
                        <p class="tab-page-text">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            Incidunt ipsum totam vero deleniti? Facere?
                        </p>
                        <p class="tab-page-text">
                            Lorem ipsum dolor sit amet, consectetur adipisicing
                            elit. Excepturi deleniti, culpa eum ratione similique
                            est magni magnam vitae beatae accusantium, modi,
                            accusamus quisquam! Quos.
                        </p>
                        <img
                            src="https://github.com/shadcn.png"
                            alt=""
                            class="tab-page-image"
                        />
                        <p class="tab-page-text">
                            Lorem ipsum dolor sit, amet consectetur adipisicing
                            elit. Quos laboriosam earum maiores architecto obcaecati
                            eligendi, atque accusamus sed dolores beatae ea minima
                            sint itaque soluta doloribus neque quasi id ratione
                            saepe amet.
                        </p>
                    </div>
                    <!-- <div class="tab-page" data-tab-page="pending">
                        <h1 class="tab-page-title">Pending</h1>
                        <p class="tab-page-text">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            Incidunt ipsum totam vero deleniti? Facere?
                        </p>
                        <p class="tab-page-text">
                            Lorem ipsum dolor sit amet, consectetur adipisicing
                            elit. Excepturi deleniti, culpa eum ratione similique
                            est magni magnam vitae beatae accusantium, modi,
                            accusamus quisquam! Quos.
                        </p>
                        <p class="tab-page-text">
                            Lorem ipsum dolor sit, amet consectetur adipisicing
                            elit. Quos laboriosam earum maiores architecto obcaecati
                            eligendi, atque accusamus sed dolores beatae ea minima
                            sint itaque soluta doloribus neque quasi id ratione
                            saepe amet.
                        </p>
                    </div>
                    <div class="tab-page" data-tab-page="paused">
                        <h1 class="tab-page-title">Paused</h1>
                        <img
                            src="https://github.com/shadcn.png"
                            alt=""
                            class="tab-page-image"
                        />
                        <p class="tab-page-text">
                            Lorem ipsum dolor sit amet, consectetur adipisicing
                            elit. Excepturi deleniti, culpa eum ratione similique
                            est magni magnam vitae beatae accusantium, modi,
                            accusamus quisquam! Quos.
                        </p>
                        <p class="tab-page-text">
                            Lorem ipsum dolor sit, amet consectetur adipisicing
                            elit. Quos laboriosam earum maiores architecto obcaecati
                            eligendi, atque accusamus sed dolores beatae ea minima
                            sint itaque soluta doloribus neque quasi id ratione
                            saepe amet.
                        </p>
                    </div> -->
                </div>
            </section>
		</div>
		<!-- end: MAIN BODY -->

	</section>
	<!-- end: MAIN -->