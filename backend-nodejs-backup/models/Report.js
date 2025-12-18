import mongoose from 'mongoose';

const reportSchema = new mongoose.Schema({
  reportId: { type: String, required: true, unique: true },
  reportType: { type: String, required: true },
  generatedDate: { type: Date, required: true },
  caller: {
    id: { type: mongoose.Schema.Types.ObjectId, ref: 'Caller' },
    name: String,
    callerId: String,
    email: String
  },
  summary: mongoose.Schema.Types.Mixed,
  callStatistics: mongoose.Schema.Types.Mixed,
  requestsCompleted: Number,
  requestsOngoing: Number,
  completedRequestsList: [mongoose.Schema.Types.Mixed],
  customerDetails: [mongoose.Schema.Types.Mixed]
});

const Report = mongoose.model('Report', reportSchema);
export default Report;
