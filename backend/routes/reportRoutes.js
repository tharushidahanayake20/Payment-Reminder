import express from 'express';
import Report from '../models/Report.js';

const router = express.Router();

// @route   GET /api/reports
// @desc    Get all performance reports
// @access  Admin
router.get('/', async (req, res) => {
  try {
    const reports = await Report.find().sort({ generatedDate: -1 });
    res.status(200).json({
      success: true,
      data: reports
    });
  } catch (error) {
    res.status(500).json({
      success: false,
      message: 'Error fetching reports',
      error: error.message
    });
  }
});

export default router;
