-- First, find the constraint name
SELECT CONSTRAINT_NAME 
FROM information_schema.TABLE_CONSTRAINTS 
WHERE TABLE_NAME = 'tblbooks' 
AND CONSTRAINT_TYPE = 'FOREIGN KEY';

-- Then drop the foreign key constraint (replace 'constraint_name' with the actual name from above)
ALTER TABLE tblbooks DROP FOREIGN KEY constraint_name;

-- Now you can modify the table structure
ALTER TABLE tblbooks 
DROP INDEX added_by;

-- If you need to recreate the foreign key constraint, use:
-- ALTER TABLE tblbooks
-- ADD CONSTRAINT fk_added_by
-- FOREIGN KEY (added_by) REFERENCES tbladmin(admin_id); 