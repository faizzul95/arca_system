# ATTENDANCE RECORD COLLEGE ACTIVITY (ARCA)

Version Release : 4.0  <br/>
Stack : PHP 8.0, JavaScript <br/>
Framework : Bootstrap v5.0, CodeIgniter 3 (CodeIgniter 3.1.13) <br/>
Database : MySQL <br/>
Category : Web Application, PWA <br/>
Status : <i> Discontinue </i> <br/>
Last update : 16/05/2023

<details> 
<summary> DESCRIPTION </summary>
<hr>
<p> This system was built to facilitate college administrators at a university in Malaysia known as UiTM (Perlis Branch). This system is used to record the attendance of university students and ensure their participation in obtaining college credit for the next semester.</p>
<p> This system was developed to replace the existing system, which is the sticker system that is obtained when students attend each activity. This system uses a dynamic QR code that will change every time it is set in order to avoid fraud among students by giving a static QR code to their friends who do not attend activities.</p>
<br/>
</details> 


<details> 
<summary> FEATURES </summary>
<hr>
  
- SECURITY
	1) XSS Protection (validate data from malicious code using middleware)
	2) Google Authenticator (Use for 2FA)
	3) Google ReCAPTCHA v2 (Reduce DDos Attack)
	4) Login Attempt (Reduce Brute Force Attack)
	5) Custom Front-end Validation in JS (Data integrity)
	6) Custom Route & Middleware (Protect URL & Page) - Thanks <a href="https://github.com/ingeniasoftware/luthier-ci" target="_blank"> Luthier CI </a> for amazing library
	7) CSRF Token & Cookie (Built in CI3)
	8) Rate Limiting Trait (API Request limitter using Middleware)

- SYSTEM
	1) Custom Model DB Query. 
	2) Job Queue (Worker) - Running in background (Thanks to <a href="https://github.com/yidas/codeigniter-queue-worker" target="_blank"> Yidas </a> for Queue Worker library)
	3) Maintenance Mode (With custom page)
	4) Blade Templating Engine (Increase security & caching) - (Credit to team <a href="https://github.com/EFTEC/BladeOne" target="_blank">BladeOne</a>)
	5) SSL Force redirect (production mode)
	6) System logger (Log error system in database & files)
	7) Audit Trail (Log data insert, update, delete in database)
	8) CRUD Log (Log data insert, update, delete in files)
	9) Cron Scheduler - (Credit to <a href="https://github.com/peppeocchi/php-cron-scheduler" target="_blank">Peppeocchi</a>)

- HELPER
	<ol type="A">
	<li> Front-end </li> 
	<ol type="1">
		<li> Call API (POST, GET), Upload API, Delete API wrapper (using axios) </li>
		<li> Dynamic modal & Form loaded </li>
		<li> Generate datatable (server-side & client-side rendering) </li>
		<li> Print DIV (use <a href="https://jasonday.github.io/printThis/" target="_blank">printThis</a> library) </li>
	</ol> 
	<br>
	<li> Backend-end </li> 
	<ol type="1">
		<li> Array helper </li>
		<li> Data Helper </li>
		<li> Date Helper </li>
		<li> Upload Helper (upload, move, compress image) </li>
		<li> QR Generate Helper (using <a href="https://github.com/endroid/qr-code" target="_blank">Endroid</a> library) </li>
		<li> Read/Import Excel (using <a href="https://github.com/PHPOffice/PhpSpreadsheet" target="_blank">PHPSpreadsheet</a> library) </li>
		<li> Mailer (using <a href="https://github.com/PHPMailer/PHPMailer" target="_blank">PHPMailer</a> library) </li>
	</ol>
	</ol>
			
- SERVICES
	1) Backup system folder (with exceptions file or folder)
	2) Backup database (MySQL tested)
	3) Upload file backup to google drive (need to configure)

- MODULE BUNDLER
	1) Concat, uglify JavaScript using Grunt JS (read more <a href="https://gruntjs.com/" target="_blank">Grunt Website</a>)

<br/>
</details> 

<details> 
<summary> COMMAND </summary>
<hr>

Command (Terminal / Command Prompt):-

<ol type="A">
	<li> Cache </li> 
		<ol type="1">
			<li> php struck clear view (remove blade cache)  </li>
			<li> php struck clear cache (remove ci session cache)  </li>
      <li> php struck clear all (remove ci session cache, blade cache & logs file)  </li>
      <li> php struck optimize (remove blade cache & logs file)  </li>
		</ol> 
	<br>
	<li> Backup (use as a ordinary cron jobs) </li> 
		<ol type="1">
			<li> php struck cron database (backup the database in folder project) </li>
			<li> php struck cron system (backup system folder in folder project) </li>
			<li> php struck cron database upload (backup the database & upload to google drive) </li>
			<li> php struck cron system upload (backup system folder & upload to google drive) </li>
		</ol> 
	<br>
	<li> Jobs (Queue Worker) </li> 
		<ol type="1">
			<li> php struck jobs (temporary run until jobs completed) </li>
			<li> php struck jobs work (temporary run until jobs completed) </li>
			<li> php struck jobs launch (permanent until services kill) - use in linux environment </li>
		</ol> 
	<br>
		<li> Cron Scheduler (Laravel Task Scheduling) </li> 
		<ol type="1">
			<li> php struck schedule:run </li>
      <li> php struck schedule:list </li>
      <li> php struck schedule:work </li>
      <li> php struck schedule:fail </li>
		</ol> 
	<br>
	<li> Module Bundler </li> 
		<ol type="1">
			<li> grunt </li>
			<li> grunt watch (keep detecting changes) </li>
		</ol> 
	<br>
</ol>
 <br/>
</details> 

<details> 
<summary> SYSTEM DEMONSTRATION </summary>
<hr>
	
WEB | PWA
:-: | :-:
[<img src="https://img.youtube.com/vi/aYsfKRpB7n4/maxresdefault.jpg" width="50%">](https://www.youtube.com/watch?v=aYsfKRpB7n4) | [<img src="https://img.youtube.com/vi/FiG1zACdtio/maxresdefault.jpg" width="50%">](https://www.youtube.com/watch?v=FiG1zACdtio)
	
</details> 
