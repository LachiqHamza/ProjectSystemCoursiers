import React, { useEffect, useState } from 'react';
import axios from 'axios';
import { Button, Card, Form, Input, DatePicker, message, Modal } from 'antd';
import moment from 'moment';
import { CheckCircleOutlined, CloseCircleOutlined, ClockCircleOutlined } from '@ant-design/icons'; // Import icons

const ClientDemandePage = () => {
  const [demandes, setDemandes] = useState([]);
  const [showForm, setShowForm] = useState(false);
  const [form] = Form.useForm();
  const [clientId, setClientId] = useState(null);

  useEffect(() => {
    // Retrieve client ID from local storage
    const storedClientId = localStorage.getItem('clientId');
    if (storedClientId) {
      setClientId(storedClientId);
    }
  }, []);

  useEffect(() => {
    if (clientId) {
      axios.get(`http://127.0.0.1:8000/api/demandes/finddemandesbyclient/${clientId}`)
        .then(response => {
          setDemandes(response.data);
        })
        .catch(error => {
          console.error('Error fetching demandes:', error);
        });
    }
  }, [clientId]);

  const handleFormSubmit = (values) => {
    const newDemande = {
      description: values.description,
      adress_source: values.adress_source,
      adress_dest: values.adress_dest,
      poids: values.poids,
      date_demande: values.date_demande.format('YYYY-MM-DD'),
      status: null, // Explicitly set as null
      date_livraison: null, // Explicitly set as null
      client: {
        id_client: clientId
      },
      admin: {
        id_admin: null // Explicitly set as null
      },
      coursier: {
        id_coursier: null // Explicitly set as null
      }
    };

    axios.post('http://127.0.0.1:8000/api/demandes/add', newDemande)
      .then(response => {
        message.success('Demande added successfully');
        setDemandes([...demandes, response.data]);
        setShowForm(false);
        form.resetFields();
      })
      .catch(error => {
        message.error('Error adding demande');
        console.error('Error:', error);
      });
  };

  // Inline styles
  const styles = {
    clientIdDisplay: {
      position: 'fixed',
      top: '10px',
      right: '10px',
      backgroundColor: '#f0f2f5',
      padding: '10px',
      borderRadius: '5px',
      boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
      fontSize: '16px'
    },
    demandeList: {
      marginTop: '20px',
      display: 'flex',
      flexWrap: 'wrap',
      gap: '16px'
    },
    card: {
      width: '300px',
      border: '1px solid #d9d9d9',
      borderRadius: '4px',
      boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)'
    },
    statusIcon: {
      fontSize: '20px',
      marginRight: '8px'
    }
  };

  const getStatusIcon = (status) => {
    switch (status) {
      case 'Completed':
        return <CheckCircleOutlined style={styles.statusIcon} />;
      case 'Cancelled':
        return <CloseCircleOutlined style={styles.statusIcon} />;
      case 'Pending':
        return <ClockCircleOutlined style={styles.statusIcon} />;
      default:
        return null;
    }
  };

  return (
    <div>
      <Button type="primary" onClick={() => setShowForm(true)}>
        Add New Demande
      </Button>
      <Modal
        title="Add New Demande"
        visible={showForm}
        onCancel={() => setShowForm(false)}
        footer={null}
      >
        <Form
          form={form}
          onFinish={handleFormSubmit}
          layout="vertical"
        >
          <Form.Item
            name="description"
            label="Description"
            rules={[{ required: true, message: 'Please input the description!' }]}
          >
            <Input />
          </Form.Item>
          <Form.Item
            name="adress_source"
            label="Address Source"
            rules={[{ required: true, message: 'Please input the address source!' }]}
          >
            <Input />
          </Form.Item>
          <Form.Item
            name="adress_dest"
            label="Address Destination"
            rules={[{ required: true, message: 'Please input the address destination!' }]}
          >
            <Input />
          </Form.Item>
          <Form.Item
            name="poids"
            label="Weight"
            rules={[{ required: true, message: 'Please input the weight!' }]}
          >
            <Input type="number" step="0.1" />
          </Form.Item>
          <Form.Item
            name="date_demande"
            label="Date of Request"
            rules={[{ required: true, message: 'Please select the date of request!' }]}
          >
            <DatePicker format="YYYY-MM-DD" />
          </Form.Item>
          {/* Status and Delivery Date fields are removed from the form */}
          <Form.Item>
            <Button type="primary" htmlType="submit">
              Submit
            </Button>
          </Form.Item>
        </Form>
      </Modal>
      <div style={styles.demandeList}>
        {demandes.map(demande => (
          <Card key={demande.id_demande} style={styles.card}>
            <Card.Meta
              title={`Demande ID: ${demande.id_demande}`}
              description={
                <>
                  <p><strong>Description:</strong> {demande.description}</p>
                  <p><strong>Address Source:</strong> {demande.adress_source}</p>
                  <p><strong>Address Destination:</strong> {demande.adress_dest}</p>
                  <p><strong>Weight:</strong> {demande.poids}</p>
                  <p><strong>Date of Request:</strong> {moment(demande.date_demande).format('YYYY-MM-DD')}</p>
                  <p><strong>Status:</strong> {getStatusIcon(demande.status)} {demande.status || 'N/A'}</p>
                  <p><strong>Delivery Date:</strong> {demande.date_livraison ? moment(demande.date_livraison).format('YYYY-MM-DD') : 'N/A'}</p>
                </>
              }
            />
          </Card>
        ))}
      </div>
    </div>
  );
};

export default ClientDemandePage;
