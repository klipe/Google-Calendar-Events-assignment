<template>
  <div>
    <br>
    <ul>
      <li style="padding:2px 0px; color: white; font-family: Arial, Helvetica, sans-serif;" v-for="event in events" :key="event.event_id" @click="openModal(event)" :class="{ selected: selectedEvent && selectedEvent.event_id === event.event_id }">
        <strong>{{ event.start_time }} - {{ event.end_time }}</strong>
      </li>
    </ul>

    <!-- Modal Component -->
    <Modal :isVisible="isModalVisible" :event="selectedEvent" @close="closeModal" />
  </div>
</template>

<script>
import Modal from './Modal.vue';

export default {
  components: {
    Modal,
  },
  props: {
    events: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      isModalVisible: false,
      selectedEvent: null,
    };
  },
  methods: {
    openModal(event) {
      this.selectedEvent = event;
      this.isModalVisible = true;
    },
    closeModal() {
      this.isModalVisible = false;
      this.selectedEvent = null;
    },
  },
};
</script>

<style scoped>
.selected {
  background-color: #9ca3af; /* Highlight selected item */
}
</style>