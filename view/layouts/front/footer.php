		</div>
		<footer class="footer">
			<div class="container">
				<p class="text-muted">PHP Forums.</p>
			</div>
		</footer>
		<!-- jQuery -->
		<script src="assets/js/jquery-1.11.1.min.js"></script>
		<!-- Bootstrap JavaScript -->
		<script src="assets/js/bootstrap.min.js"></script>
		<!-- Custom JavaScript -->
		<script src="assets/js/cust/script.js"></script>
		<?php if (isset($_auto_load_js)): ?>
		<?php foreach ($_auto_load_js as $js): echo $js; endforeach; ?>
		<?php endif; ?>
	</body>
</html>