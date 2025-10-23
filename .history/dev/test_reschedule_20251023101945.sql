-- Test query to verify reschedule functionality
-- This will show any appointments with reschedule data

SELECT 
    a.id,
    a.patient_id,
    a.doctor_id,
    a.status,
    a.starts_at,
    a.ends_at,
    a.proposed_datetime,
    a.proposed_by,
    a.reschedule_status,
    a.reschedule_message,
    a.reschedule_expires_at,
    u1.name as patient_name,
    u2.name as doctor_name
FROM appointments a
LEFT JOIN Users u1 ON u1.id = a.patient_id  
LEFT JOIN Users u2 ON u2.id = a.doctor_id
WHERE a.reschedule_status != 'none' OR a.proposed_datetime IS NOT NULL
ORDER BY a.created_at DESC;