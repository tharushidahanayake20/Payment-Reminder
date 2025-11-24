import mongoose from 'mongoose';

const userSchema = new mongoose.Schema({
  name: {
    type: String,
    required: true
  },

  email: {
    type: String,
    required: true,
    unique: true,
    lowercase: true
  },

  phone: {
    type: String,
    default: ''
  },

  password: {
    type: String,
    required: true
  },

  avatar: {
    type: String,  // base64 image
    default: ''
  },

  preferences: {
    norification: {
      emailNotifications: { type: Boolean, default: true },
      paymentReminder: { type: Boolean, default: true },
      callNotifications: { type: Boolean, default: false }
    },
    system: {
      darkMode: { type: Boolean, default: false },
      language: { type: String, default: 'English' },
      timezone: { type: String, default: 'UTC' }
    }
  }

}, {
  timestamps: true
});

export default mongoose.model('User', userSchema);
