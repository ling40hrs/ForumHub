-- Migration 0001: Add background_url column to communities table

ALTER TABLE communities
  ADD COLUMN background_url VARCHAR(255) DEFAULT NULL
  AFTER owner_id;

-- Update existing communities with Pexels background images
UPDATE communities SET background_url = 'https://images.pexels.com/photos/1181271/pexels-photo-1181271.jpeg?auto=compress&cs=tinysrgb&w=1260&h=300&fit=crop' WHERE slug = 'webdev';
UPDATE communities SET background_url = 'https://images.pexels.com/photos/374074/pexels-photo-374074.jpeg?auto=compress&cs=tinysrgb&w=1260&h=300&fit=crop' WHERE slug = 'php';
UPDATE communities SET background_url = 'https://images.pexels.com/photos/196644/pexels-photo-196644.jpeg?auto=compress&cs=tinysrgb&w=1260&h=300&fit=crop' WHERE slug = 'design';
UPDATE communities SET background_url = 'https://images.pexels.com/photos/5203849/pexels-photo-5203849.jpeg?auto=compress&cs=tinysrgb&w=1260&h=300&fit=crop' WHERE slug = 'mysql';
UPDATE communities SET background_url = 'https://images.pexels.com/photos/4158/apple-iphone-smartphone-desk.jpg?auto=compress&cs=tinysrgb&w=1260&h=300&fit=crop' WHERE slug = 'student-projects';
