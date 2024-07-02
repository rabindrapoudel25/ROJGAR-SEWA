document.addEventListener('DOMContentLoaded', function() {
    fetchJobs();
    populateUserDetails();

    document.getElementById('search-btn').addEventListener('click', function() {
        fetchJobs();
    });

    document.getElementById('back-btn').addEventListener('click', function() {
        document.getElementById('job-listing').style.display = 'grid';
        document.getElementById('job-details-container').style.display = 'none';
    });

    document.getElementById('apply-btn-details').addEventListener('click', function() {
        document.getElementById('apply-form').style.display = 'block';
    });

    document.getElementById('submit-application-btn').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default form submission
        const applicantName = document.getElementById('applicant-name').value;
        const applicantEmail = document.getElementById('applicant-email').value;
        const coverLetter = document.getElementById('cover-letter').value;
        const cvUpload = document.getElementById('cv-upload').files[0];

        if (applicantName && applicantEmail && coverLetter && cvUpload) {
            submitApplication(applicantName, applicantEmail, coverLetter, cvUpload);
        } else {
            alert('Please fill in all fields and upload your CV.');
        }
    });
});

function fetchJobs() {
    const searchTitle = document.getElementById('search-title').value;
    const searchLocation = document.getElementById('search-location').value;

    fetch('fetch_jobs.php')
        .then(response => response.json())
        .then(data => {
            const filteredJobs = data.filter(job => {
                return (searchTitle === '' || job.title.toLowerCase().includes(searchTitle.toLowerCase())) &&
                       (searchLocation === '' || job.location.toLowerCase().includes(searchLocation.toLowerCase()));
            });

            displayJobListings(filteredJobs);
        })
        .catch(error => console.error('Error fetching jobs:', error));
}

function displayJobListings(jobs) {
    const jobListing = document.getElementById('job-listing');
    jobListing.innerHTML = '';

    jobs.forEach(job => {
        const jobCard = document.createElement('div');
        jobCard.classList.add('job-card');
        jobCard.innerHTML = `
            <h2>${job.title}</h2>
            <p><strong>Company:</strong> ${job.company}</p>
            <p><strong>Location:</strong> ${job.location}</p>
            <p><strong>Salary:</strong> ${job.salary}</p>
            <p><strong>Open Date:</strong> ${job.dateOpen}</p>
            <p><strong>End Date:</strong> ${job.dateEnd}</p>
            <button class="apply-btn">Apply Now</button>
        `;
        jobCard.querySelector('.apply-btn').addEventListener('click', () => displayJobDetails(job));
        jobListing.appendChild(jobCard);
    });
}

function displayJobDetails(job) {
    const jobDetails = document.getElementById('job-details');
    jobDetails.innerHTML = `
        <h2>${job.title}</h2>
        <p><strong>Company:</strong> ${job.company}</p>
        <p><strong>Location:</strong> ${job.location}</p>
        <p><strong>Salary:</strong> ${job.salary}</p>
        <p><strong>Description:</strong> ${job.description}</p>
        <p><strong>Open Date:</strong> ${job.dateOpen}</p>
        <p><strong>End Date:</strong> ${job.dateEnd}</p>
        <p><strong>Interview Date:</strong> ${job.interviewDate}</p>
        <p><strong>Start Time:</strong> ${job.startTime}</p>
        <p><strong>End Time:</strong> ${job.endTime}</p>
        <p><strong>Email:</strong> ${job.businessEmail}</p>
        <p><strong>Phone Number:</strong> ${job.businessPhoneNumber}</p>
        <img src="${job.profileImage}" alt="${job.company} logo">
    `;
    
    document.getElementById('job-listing').style.display = 'none';
    document.getElementById('job-details-container').style.display = 'flex';
    document.getElementById('job-details').dataset.jobPostId = job.id; // Set job post ID for later use
}

function populateUserDetails() {
    fetch('fetch_user_details.php')
        .then(response => response.json())
        .then(user => {
            if (user.success !== false) {
                document.getElementById('applicant-name').value = user.fullname;
                document.getElementById('applicant-email').value = user.email;
            } else {
                console.error('Error fetching user details:', user.message);
            }
        })
        .catch(error => console.error('Error fetching user details:', error));
}

function submitApplication(applicantName, applicantEmail, coverLetter, cvUpload) {
    const formData = new FormData();
    formData.append('applicant_name', applicantName);
    formData.append('applicant_email', applicantEmail);
    formData.append('cover_letter', coverLetter);
    formData.append('cv', cvUpload);
    formData.append('job_post_id', document.getElementById('job-details').dataset.jobPostId);

    fetch('save_application.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Success:', data.message);
            document.getElementById('success-message').style.display = 'block';
            document.getElementById('apply-form').reset(); // Reset form fields after successful submission
            document.getElementById('apply-form').style.display = 'none';
        } else {
            alert(data.message || 'An error occurred while submitting the application.');
        }
    })
    .catch(error => console.error('Error submitting application:', error));
}
document.getElementById('submit-application-btn').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent default form submission
    const applicantName = document.getElementById('applicant-name').value;
    const applicantEmail = document.getElementById('applicant-email').value;
    const coverLetter = document.getElementById('cover-letter').value;
    const cvUpload = document.getElementById('cv-upload').files[0];
    const jobPostId = document.getElementById('job-details').dataset.jobPostId;

    console.log("Submitting application with details: ", {
        applicantName, applicantEmail, coverLetter, jobPostId, cvUpload
    });

    if (applicantName && applicantEmail && coverLetter && cvUpload) {
        submitApplication(applicantName, applicantEmail, coverLetter, cvUpload, jobPostId);
    } else {
        alert('Please fill in all fields and upload your CV.');
    }
});
});

function fetchJobs() {
const title = document.getElementById('search-title').value;
const location = document.getElementById('search-location').value;
const jobListingContainer = document.getElementById('job-listing');

console.log(`Fetching jobs with title: ${title}, location: ${location}`);

fetch(`fetch_jobs.php?title=${title}&location=${location}`)
    .then(response => response.json())
    .then(data => {
        console.log('Jobs fetched from server:', data);
        jobListingContainer.innerHTML = ''; // Clear existing job listings

        if (data.success) {
            data.jobs.forEach(job => {
                const jobCard = document.createElement('div');
                jobCard.classList.add('job-card');
                jobCard.innerHTML = `
                    <h3>${job.title}</h3>
                    <p>${job.description}</p>
                    <button class="view-details-btn" data-job-id="${job.id}">View Details</button>
                `;
                jobListingContainer.appendChild(jobCard);
            });

            document.querySelectorAll('.view-details-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const jobId = this.dataset.jobId;
                    fetchJobDetails(jobId);
                });
            });
        } else {
            jobListingContainer.innerHTML = '<p>No job vacancies found.</p>';
        }
    })
    .catch(error => {
        console.error('Error fetching jobs:', error);
    });
}

function fetchJobDetails(jobId) {
fetch(`fetch_job_details.php?id=${jobId}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const jobDetailsContainer = document.getElementById('job-details');
            jobDetailsContainer.dataset.jobPostId = jobId;
            jobDetailsContainer.innerHTML = `
                <h3>${data.job.title}</h3>
                <p>${data.job.description}</p>
                <p>Location: ${data.job.location}</p>
                <p>Company: ${data.job.company}</p>
            `;
            document.getElementById('job-listing').style.display = 'none';
            document.getElementById('job-details-container').style.display = 'block';
        } else {
            alert('Error fetching job details.');
        }
    })
    .catch(error => {
        console.error('Error fetching job details:', error);
    });
}

function submitApplication(applicantName, applicantEmail, coverLetter, cvUpload, jobPostId) {
const formData = new FormData();
formData.append('applicant_name', applicantName);
formData.append('applicant_email', applicantEmail);
formData.append('cover_letter', coverLetter);
formData.append('cv', cvUpload);
formData.append('job_post_id', jobPostId);

fetch('save_application.php', {
    method: 'POST',
    body: formData
})
.then(response => response.json())
.then(data => {
    console.log('Response from server: ', data);
    if (data.success) {
        console.log('Success:', data.message);
        const successMessageElement = document.getElementById('success-message');
        successMessageElement.innerHTML = `Thank you for your application! Please visit the office on ${data.interviewDate} from ${data.startTime} to ${data.endTime}.`;
        successMessageElement.style.display = 'block';
        document.getElementById('apply-form').reset(); // Reset form fields after successful submission
        document.getElementById('apply-form').style.display = 'none';
    } else {
        alert(data.message || 'An error occurred while submitting the application.');
    }
})
.catch(error => {
    console.error('Error submitting application:', error);
});
}
