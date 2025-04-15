-- Run this SQL in phpMyAdmin to create the job_listings table
-- Replace 'wp_' with your actual WordPress table prefix if different

CREATE TABLE IF NOT EXISTS `QEE_job_listings` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jn INT,
    published TINYINT(1) DEFAULT 0,
    county_job TINYINT(1) DEFAULT 0,
    credential_job TINYINT(1) DEFAULT 0,
    job_title VARCHAR(255) NOT NULL,
    employer VARCHAR(255) NOT NULL,
    location VARCHAR(255),
    job_type VARCHAR(100),
    compensation VARCHAR(100),
    about_the_employer TEXT,
    shift_details VARCHAR(255),
    benefits TEXT,
    job_description TEXT,
    skills_and_qualifications TEXT,
    education VARCHAR(255),
    additional_information TEXT,
    job_image_link VARCHAR(255),
    account_logo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample data (optional)
INSERT INTO `QEE_job_listings` (jn, published, county_job, credential_job, job_title, employer, location, job_type, compensation, about_the_employer, shift_details, benefits, job_description, skills_and_qualifications, education, additional_information, job_image_link, account_logo) VALUES
(121, 1, 0, 1, 'Pharmacy Technician', 'University of Chicago Medicine', 'Chicago, IL', 'Full Time', '20.00 - 30.00 per hour', 'The University of Chicago Medicine takes pride in providing the highest level of care to the community.', '1st Shift; 2nd Shift; 3rd Shift; Any Shift; Weekends', 'Dental; Life Insurance; Long Term Disability; Medical', 'Be a part of a world-class academic healthcare system. Transports medications, IVs, TPNs (total parenteral nutrition) to and from locations.', 'Transports medications, IVs, TPNs (total parenteral nutrition) to and from locations.', 'Certification/License', '', 'https://sfcf.file.force.com/servlet/servlet.ImageServer?id=', 'https://sfcf.file.force.com/servlet/servlet.ImageServer?id='),

(125, 1, 0, 0, 'Relationship Banker- Floater - Various Locations', 'Old National Bank', 'Chicago, IL', 'Full Time', '17.00 - 29.75 per hour', 'Old National Bank has been serving clients and communities for over 100 years.', '1st Shift; Weekends', 'Dental; Medical; Paid Training/Personal Development', 'The Relationship Banker develops and cultivates long-term client relationships.', 'Consults with clients/prospective clients over the phone or in person.', 'High School Diploma/GED', '', 'https://sfcf.file.force.com/servlet/servlet.ImageServer?id=', 'https://sfcf.file.force.com/servlet/servlet.ImageServer?id='),

(136, 1, 0, 0, 'Customer Advocate I-Bilingual-English-Spanish', 'BCBS Pilsen', 'Chicago, IL', 'Full Time', '18.00 per hour', 'Blue Cross and Blue Shield of Illinois is proud to provide comprehensive health insurance to millions of members.', '1st Shift', 'Dental; Employee Assistance; Life Insurance; Long Term Disability', 'Job Requirements: Bilingual (English and Spanish)', 'Assists members and physicians promptly through phone, email, and chat communications.', 'High School Diploma/GED', '', 'https://sfcf.file.force.com/servlet/servlet.ImageServer?id=', 'https://sfcf.file.force.com/servlet/servlet.ImageServer?id='),

(141, 1, 0, 0, 'Food Service Worker', 'Shirley Ryan Ability Lab', 'Chicago, IL', 'Part-Time Plus', '16.20-20.82 per hour', 'Shirley Ryan AbilityLab is the global leader in physical medicine and rehabilitation for adults and children with severe and complex conditions.', 'Any Shift; Weekends', '', 'The Food Service Worker will maintain safe, accurate food preparation and service.', 'Provides courteous and timely set-up and service to patients and staff.', 'High School Diploma/GED', '', 'https://sfcf.file.force.com/servlet/servlet.ImageServer?id=', 'https://sfcf.my.salesforce.com/servlet/servlet.ImageServer?id='),

(173, 1, 0, 1, 'Medical Assistant', 'Rush University Medical Center', 'Chicago, IL', 'Full Time', '18.87 - 29.73 per hour', 'Rush University Medical Center is a leading academic hospital dedicated to providing the highest quality patient care, education, and research.', '1st Shift; Weekends', 'Dental; Employee Assistance; Life Insurance; Long Term Disability', 'Medical Assistant ? RUSH University Medical Center', 'Performs patient intake such as vital signs, height, weight.', 'High School Diploma/GED', '', 'https://sfcf.file.force.com/servlet/servlet.ImageServer?id=', 'https://sfcf.file.force.com/servlet/servlet.ImageServer?id='),

(183, 1, 0, 0, 'Client Service Representative-Teller- Homewood IL', 'Old National Bank', 'Homewood, IL', 'Full Time', '17.00 - 27.50 per hour', 'Old National Bank has been serving clients and communities with comprehensive financial services for over a century.', '1st Shift; Weekends', 'Dental; Medical; Retirement/401K; Vacation; Vision', 'Old National Bank has been serving clients and communities for decades with financial expertise and personalized service.', 'Introduces bank products and services to customers.', 'High School Diploma/GED', '', 'https://sfcf.file.force.com/servlet/servlet.ImageServer?id=', 'https://sfcf.file.force.com/servlet/servlet.ImageServer?id='),

(193, 1, 1, 1, 'Pharmacy Technician', 'Northwestern Memorial HealthCare', 'Chicago, IL', 'Full Time', '22.15 per hour', 'Life at Northwestern Medicine means being a part of a team that values excellence, growth, and diversity.', 'Any Shift', '', 'Prepares medication orders for inpatients.', 'Under direction of registered pharmacist, performs technical pharmacy operations.', 'Certification/License', '', 'https://sfcf.file.force.com/servlet/servlet.ImageServer?id=', 'https://sfcf.file.force.com/servlet/servlet.ImageServer?id=');