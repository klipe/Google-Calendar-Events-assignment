<template>
    <div class="calendar-container">
      <h1 style="font-size: 40px; color: white;">Google Calendar Events</h1>
      <div class="button-group">
        <button class="custom-btn google-btn" @click="signInWithGoogle">Sign in with Google</button>
        <button class="custom-btn fetch-btn" @click="fetchCalendarEvents">Fetch Calendar Events</button>
      </div>
      <calendar-events :events="events" />
    </div>
  </template>
  
  <style scoped>
  .calendar-container {
    text-align: center;
    padding: 20px;
  }

  
  .button-group {
    margin-top: 20px;
    display: flex;
    justify-content: center;
    gap: 10px;
  }
  
  .custom-btn {
    padding: 10px 20px;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease, color 0.3s ease;
  }
  
  .google-btn {
    background-color: #4285f4;
    color: white;
  }
  
  .google-btn:hover {
    background-color: #357ae8;
  }
  
  .fetch-btn {
    background-color: #34a853;
    color: white;
  }
  
  .fetch-btn:hover {
    background-color: #2c8c47;
  }
  
  .custom-btn:focus {
    outline: none;
  }
  
  </style>
  <script>
  import axios from 'axios';
  import CalendarEvents from './CalendarEvents.vue'; // Adjust the path as needed
  
  export default {
    components: {
      CalendarEvents,
    },
    data() {
      return {
        events: [],
      };
    },
    created() {
      this.loadEvents();
    },
    methods: {
      async fetchCalendarEvents() {
        try {
          const response = await axios.get('/calendar');
          console.log('Fetched events:', response.data); // Check data format
          this.events = Array.isArray(response.data) ? response.data : [];
        } catch (error) {
          console.error('Error fetching events:', error);
        }
      },
      signInWithGoogle() {
        window.location.href = '/google/redirect'; // Full page redirect for Google OAuth
      },
      async loadEvents() {
        try {
          const response = await axios.get('/calendar-events');
          this.events = response.data; // Set the events data property
        } catch (error) {
          console.error('Error fetching events:', error);
        }
      }
    }
  };
  </script>