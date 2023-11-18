<body style="color: black; font-size: 18px;">
   <div style="background:#fff; padding: 1rem; border: 4px solid black;">
        <p>Good Day, {{ $studentName }}!</p>
        <p>You have been invited to join <strong>{{$classroomName}}</strong> class.</p>
        <p>To join, please go to <a href="https://teachertribe-elearn.fly.dev">https://teachertribe-elearn.fly.dev</a> with the following credentials:</p>
        <p>
            <span>Email: {{$email}}</span>
            <br>
            <span>Password: {{$password}}</span>
        </p>
        <p>You may change your password after you logged in.</p>
        <p>
            <span>Happy Coding!</span>
            <br>
            <span>-Teacher Tribe</span>
        </p>
   </div>
</body>