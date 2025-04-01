<div style="overflow-y: hidden;">
    <style>
        :root {
  --level-1: #f2feff;
  --level-2: #f5cc7f;
  --level-3: #7b9fe0;
  --level-4: #f27c8d;
  --black: black;
}

* {
  padding: 0;
  margin: 0;
  box-sizing: border-box;
}

ol {
  list-style: none;
}

body {
  margin: 50px 0 100px;
  text-align: center;
  font-family: "Inter", sans-serif;
}

.container {
  width: 1000px;
 
  padding: 0 10px;
  margin: 10px 14px;
  
}

.rectangle {
  position: relative;
  padding: 20px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
}


/* LEVEL-1 STYLES
–––––––––––––––––––––––––––––––––––––––––––––––––– */
.level-1 {
  width: 50%;
  margin: 0 auto 40px;
  background: var(--level-1);
}

.level-1::before {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  transform: translateX(-50%);
  width: 1px;
  height: 20px;
  background: var(--black);
}


/* LEVEL-2 STYLES
–––––––––––––––––––––––––––––––––––––––––––––––––– */
.level-2-wrapper {
  position: relative;
  display: grid;
  grid-template-columns: repeat(2, 1fr);
}

.level-2-wrapper::before {
  content: "";
  position: absolute;
  top: -20px;
  left: 15%;
  width: 60%;
  height: 1px;
  background: var(--black);
}

.level-2-wrapper::after {
  flex-wrap: wrap;
  display: none;
  content: "";
  position: absolute;
  left: -20px;
  bottom: -20px;
  width: calc(100% + 30px);
  height: 1px;
  background: var(--black);
}

.level-2-wrapper li {
  position: relative;
}



.level-2 {
  width: 0%;
  margin: 0 auto 40px;
  background: var(--level-2);
}

.level-2::before {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  transform: translateX(-50%);
  width: 1px;
  height: 20px;
  background: var(--black);
}

.level-2::after {
  display: none;
  content: "";
  position: absolute;
  top: 50%;
  left: 0%;
  transform: translate(-100%, -50%);
  width: 40px;
  height: 1px;
  background: var(--black);
}


/* LEVEL-3 STYLES
–––––––––––––––––––––––––––––––––––––––––––––––––– */
.level-3-wrapper {
  position: relative;
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  grid-column-gap: 50px;
  width: 90%;
  margin: 0 auto;
}

.level-3-wrapper::before {
  content: "";
  position: absolute;
  top: -20px;
  left: calc(25% - 5px);
  width: calc(50% + 10px);
  height: 1px;
  background: var(--black);
}

.level-3-wrapper > li::before {
  content: "";
  position: absolute;
  top: 0;
  left: 50%;
  transform: translate(-50%, -100%);
  width: 1px;
  height: 20px;
  background: var(--black);
}

.level-3 {
  margin-bottom: 20px;
  background: var(--level-3);
}


/* LEVEL-4 STYLES
–––––––––––––––––––––––––––––––––––––––––––––––––– */
.level-4-wrapper {
  position: relative;
  width: 80%;
  margin-left: auto;
}

.level-4-wrapper::before {
  content: "";
  position: absolute;
  top: -20px;
  left: -20px;
  width: 2px;
  height: calc(100% + 20px);
  background: var(--black);
}

.level-4-wrapper li + li {
  margin-top: 20px;
}

.level-4 {
  font-weight: normal;
  background: var(--level-4);
}

.level-4::before {
  content: "";
  position: absolute;
  top: 50%;
  left: 0%;
  transform: translate(-100%, -50%);
  width: 20px;
  height: 1px;
  background: var(--black);
}


/* MQ STYLES
–––––––––––––––––––––––––––––––––––––––––––––––––– */
@media screen and (max-width: 700px) {
  .rectangle {
    padding: 20px 10px;
  }

  .level-1,
  .level-2 {
    width: 100%;
  }

  .level-1 {
    margin-bottom: 20px;
  }

  .level-1::before,
  .level-2-wrapper > li::before {
    display: none;
  }
  
  .level-2-wrapper,
  .level-2-wrapper::after,
  .level-2::after {
    display: block;
  }

  .level-2-wrapper {
    width: 90%;
    margin-left: 10%;
  }

  .level-2-wrapper::before {
    left: -20px;
    width: 1px;
    height: calc(100% + 40px);
  }

  .level-2-wrapper > li:not(:first-child) {
    margin-top: 50px;
  }
}


/* FOOTER
–––––––––––––––––––––––––––––––––––––––––––––––––– */
.page-footer {
  position: fixed;
  right: 0;
  bottom: 20px;
  display: flex;
  align-items: center;
  padding: 5px;
}

.page-footer a {
  margin-left: 4px;
}
.level-1.rectangle {
    gap:10px;
    margin-left: 353px;
    display: flex; /* Arrange items in a row */
    align-items: center; /* Align items vertically */
    border: 1px solid #778899; /* Border color */
    border-radius: 10px; /* Rounded corners */
    border-left: 5px solid #7d96f0;; /* Right border with a different color */
    padding: 5px;
    width: 20%; /* Fit content width */
    background-color: #edf3ff;; /* Light background */
}
.level-3.rectangle {
    gap:20px;
    display: flex; /* Arrange items in a row */
    align-items: center; /* Align items vertically */
    border: 1px solid #778899; /* Border color */
    border-radius: 10px; /* Rounded corners */
    border-left: 5px solid #37c2cc; /* Right border with a different color */
    padding: 5px;
    width:200px; /* Fit content width */
    background-color: #f2feff; /* Light background */
}
.profile-img {
    width: 50px; /* Adjust size as needed */
    height: 50px;
    border-radius: 50%; /* Make it circular */
    margin-right: 15px; /* Space between image and text */
    object-fit: cover; /* Ensure it crops properly */
}

.chairman-info h1 {
    margin: 0;
    font-size: 18px;
    color: #333;
}

.chairman-info p {
    margin: 2px 0;
    font-size: 14px;
    color: #555;
    white-space: nowrap; /* Prevent text from wrapping */
    overflow: hidden; /* Hide overflowing text */
    text-overflow: ellipsis; /* Add "..." at the end of truncated text */
    max-width: 110px; /* Adjust based on your design */
    display: block; /* Ensure it applies properly */
    text-align: center;
    font-size: 10px;
}
.chairman-heading {

    margin: 0;
    font-size: 12px;
    font-weight: bold;
    text-align: center;
    text-overflow: ellipsis;
    max-width: 130px;
    overflow: hidden;
    white-space: nowrap;

  
}

    </style>
<div class="container">
 
            <div class="level-1 rectangle">
                 
                    @if(!empty($chairman->image) && ($chairman->image !== 'null'))
                                <div class="employee-profile-image-container">
                                    <img class="rounded-circle" height="35px" width="35px" src="data:image/jpeg;base64,{{($chairman->image)}}">
                                </div>
                                @else
                                @if($chairman->gender === "Male")
                                <div class="employee-profile-image-container">
                                    <img src="{{ asset('images/male-default.png') }}" class="employee-profile-image-placeholder rounded-circle" height="33" width="33">
                                </div>
                                @elseif($chairman->gender === "Female")
                                <div class="employee-profile-image-container">
                                    <img src="{{ asset('images/female-default.jpg') }}" class="employee-profile-image-placeholder rounded-circle" height="33" width="33">
                                </div>
                                @else
                                <div class="employee-profile-image-container">
                                    <img src="{{ asset('images/user.jpg') }}" class="employee-profile-image-placeholder rounded-circle" height="35px" width="35px">
                                </div>
                                @endif

                                @endif
                <div class="chairman-info">
                    <h6 class="chairman-heading"title="{{ ucwords(strtolower($chairman->first_name)) }} {{ ucwords(strtolower($chairman->last_name)) }}">{{ ucwords(strtolower($chairman->first_name)) }} {{ ucwords(strtolower($chairman->last_name)) }}</h6>
                    <p>ID: {{ $chairman->emp_id }}</p>
                    <p class="job-role"title="{{ $chairman->job_role }}">Role: {{ $chairman->job_role }}</p>
                </div>
            </div>
    <ol class="level-2-wrapper">
        @foreach($hierarchy as $director)
            <li>
                
                @if(count($director['subordinates']) > 0)
                    <ol class="level-3-wrapper">
                        @foreach($director['subordinates'] as $manager)
                            <li>
                            <div class="level-3 rectangle">

                                @if(!empty( $manager['info']->image ) && ($manager['info']->image !== 'null'))
                                <div class="employee-profile-image-container">
                                <img class="rounded-circle" height="35px" width="35px"src="data:image/jpeg;base64,{{($manager['info']->image)}}">
                                </div>
                                @else
                                @if($manager['info']->gender === "Male")
                                <div class="employee-profile-image-container">
                                    <img src="{{ asset('images/male-default.png') }}" class="employee-profile-image-placeholder rounded-circle" height="33" width="33">
                                </div>
                                @elseif($manager['info']->gender === "Female")
                                <div class="employee-profile-image-container">
                                    <img src="{{ asset('images/female-default.jpg') }}" class="employee-profile-image-placeholder rounded-circle" height="33" width="33">
                                </div>
                                @else
                                <div class="employee-profile-image-container">
                                    <img src="{{ asset('images/user.jpg') }}" class="employee-profile-image-placeholder rounded-circle" height="35px" width="35px">
                                </div>
                                @endif

                                @endif
                                    <div class="chairman-info">
                                        <h6 class="chairman-heading" title="{{ ucwords(strtolower($manager['info']->first_name)) }} {{ ucwords(strtolower($manager['info']->last_name)) }}">{{ ucwords(strtolower($manager['info']->first_name)) }} {{ ucwords(strtolower($manager['info']->last_name)) }}</h6>
                                        <p>ID: {{ $manager['info']->emp_id }}</p>
                                        <p class="job-role"title="{{ $manager['info']->job_role }}">Role: {{ $manager['info']->job_role }}</p>
                                    </div>
                            </div>
                                
                               
                            </li>
                        @endforeach
                    </ol>
                @endif
            </li>
        @endforeach
    </ol>
  
</div>
</div>