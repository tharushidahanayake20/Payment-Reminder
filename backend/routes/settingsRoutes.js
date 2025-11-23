import express from 'express';
import {
  getSettings,
  updateProfile,
  changePassword,
  updatePreferences
} from '../controllers/settingsController.js';
import isAuthenticated from '../middleware/isAuthenticated.js';

const router = express.Router();

router.get('/', isAuthenticated, getSettings);
router.put('/profile', isAuthenticated, updateProfile);
router.put('/password', isAuthenticated, changePassword);
router.get("/settings", isAuthenticated, getSettings);
router.put('/preferences', isAuthenticated, updatePreferences);

export default router;