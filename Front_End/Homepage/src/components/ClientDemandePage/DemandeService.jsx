import axios from 'axios';

const API_URL = 'http://localhost:8000/api/demandes';

// Fetch all demandes
const getAllDemandes = async () => {
  try {
    const response = await axios.get(API_URL);
    return response.data;
  } catch (error) {
    console.error('Error fetching all demandes:', error);
    throw error;
  }
};

// Create a new demande
const createDemande = async (data) => {
  try {
    const response = await axios.post(`${API_URL}/add`, data);
    return response.data;
  } catch (error) {
    console.error('Error creating demande:', error);
    throw error;
  }
};

// Update an existing demande
const updateDemande = async (id, data) => {
  try {
    const response = await axios.put(`${API_URL}/${id}`, data);
    return response.data;
  } catch (error) {
    console.error('Error updating demande:', error);
    throw error;
  }
};

// Delete a demande
const deleteDemande = async (id) => {
  try {
    await axios.delete(`${API_URL}/${id}`);
  } catch (error) {
    console.error('Error deleting demande:', error);
    throw error;
  }
};

// Fetch demandes by coursier ID
const getDemandesByCoursier = async (coursierId) => {
  try {
    const response = await axios.get(`${API_URL}/demandes/${coursierId}`);
    return response.data;
  } catch (error) {
    console.error('Error fetching demandes by coursier:', error);
    throw error;
  }
};

// Fetch demandes with null admin and coursier
const getDemandesWithNullAdminAndCoursier = async () => {
  try {
    const response = await axios.get(`${API_URL}/finddemandes/newdemandes`);
    return response.data;
  } catch (error) {
    console.error('Error fetching demandes with null admin and coursier:', error);
    throw error;
  }
};

// Count demandes with null admin and coursier
const countDemandesWithNullAdminAndCoursier = async () => {
  try {
    const response = await axios.get(`${API_URL}/finddemandes/newdemandes/count`);
    return response.data;
  } catch (error) {
    console.error('Error counting demandes with null admin and coursier:', error);
    throw error;
  }
};

const DemandeService = {
  getAllDemandes,
  createDemande,
  updateDemande,
  deleteDemande,
  getDemandesByCoursier,
  getDemandesWithNullAdminAndCoursier,
  countDemandesWithNullAdminAndCoursier,
};

export default DemandeService;
