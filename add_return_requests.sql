USE lms_database;

-- Add sample return requests
INSERT INTO return_req (BookId, RollNo, Status, requested_date) VALUES 
('B001', 'CS1001', 'Requested', DATE_SUB(CURDATE(), INTERVAL 2 DAY)),
('B003', 'EC2001', 'Requested', DATE_SUB(CURDATE(), INTERVAL 1 DAY)),
('B005', 'ME3001', 'Requested', CURDATE());

-- Verify the data
SELECT rr.*, b.Title, u.Name 
FROM return_req rr 
JOIN book b ON rr.BookId = b.BookId 
JOIN user u ON rr.RollNo = u.RollNo 
WHERE rr.Status = 'Requested';