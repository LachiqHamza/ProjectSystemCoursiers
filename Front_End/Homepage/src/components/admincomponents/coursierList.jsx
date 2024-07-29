import React, { useState, useEffect } from 'react';
import { Table, Button, Modal, Form, Input, DatePicker, notification } from 'antd';
import axios from 'axios';
import { EditOutlined, DeleteOutlined, PlusOutlined, KeyOutlined } from '@ant-design/icons';
import moment from 'moment';

const { Column } = Table;

const CoursierList = () => {
  const [coursiers, setCoursiers] = useState([]);
  const [isModalVisible, setIsModalVisible] = useState(false);
  const [form] = Form.useForm();
  const [editingCourcier, setEditingCourcier] = useState(null);

  useEffect(() => {
    fetchCoursiers();
  }, []);

  const fetchCoursiers = async () => {
    try {
      const response = await axios.get('http://localhost:8000/api/courciers/all');
      setCoursiers(response.data);
    } catch (error) {
      console.error('Error fetching coursiers:', error);
    }
  };

  const handleAddCourcier = async (values) => {
    const addData = {
      ...values,
      Date_intergration: values.Date_intergration.format('YYYY-MM-DD'),
    };

    try {
      const response = await axios.post('http://localhost:8000/api/courciers/add', addData);
      setCoursiers([...coursiers, response.data]);
      setIsModalVisible(false);
      form.resetFields();
      notification.success({
        message: 'Success',
        description: 'Coursier added successfully.',
        icon: <PlusOutlined style={{ color: '#52c41a' }} />,
      });
    } catch (error) {
      console.error('Error adding coursier:', error);
    }
  };

  const handleUpdateCourcier = async (values) => {
    const updateData = {
      nom: values.nom,
      prenom: values.prenom,
      tele: values.tele,
      salaire: values.salaire,
      passwd: values.password || editingCourcier.email,
    };

    try {
      const response = await axios.put(`http://localhost:8000/api/courciers/${editingCourcier.id}/update`, updateData);
      const updatedCoursiers = coursiers.map(courcier =>
        courcier.id === response.data.id ? response.data : courcier
      );
      setCoursiers(updatedCoursiers);
      setEditingCourcier(null);
      setIsModalVisible(false);
      notification.success({
        message: 'Success',
        description: 'Coursier updated successfully.',
        icon: <EditOutlined style={{ color: '#1890ff' }} />,
      });
    } catch (error) {
      console.error('Error updating coursier:', error);
    }
  };

  const handleDeleteCourcier = async (id) => {
    try {
      await axios.delete(`http://localhost:8000/api/courciers/${id}`);
      setCoursiers(coursiers.filter(courcier => courcier.id !== id));
      notification.success({
        message: 'Success',
        description: 'Coursier deleted successfully.',
        icon: <DeleteOutlined style={{ color: '#ff4d4f' }} />,
      });
    } catch (error) {
      console.error('Error deleting coursier:', error);
    }
  };

  const showModal = (courcier = null) => {
    if (courcier) {
      const formattedCourcier = {
        nom: courcier.name,
        prenom: courcier.lastname,
        tele: courcier.tele,
        salaire: courcier.salaire,
      };
      form.setFieldsValue(formattedCourcier);
      setEditingCourcier(courcier);
    } else {
      form.resetFields();
      setEditingCourcier(null);
    }
    setIsModalVisible(true);
  };

  const resetPassword = () => {
    if (editingCourcier) {
      form.setFieldsValue({ password: editingCourcier.email });
    }
  };

  return (
    <>
      <Button 
        type="primary" 
        onClick={() => showModal()} 
        icon={<PlusOutlined />} 
        style={{ marginBottom: 16 }}
      >
        Add Coursier
      </Button>
      <Table 
        dataSource={coursiers} 
        rowKey="id" 
        bordered 
        pagination={{ pageSize: 5 }}
        style={{ marginBottom: 20 }}
      >
        <Column 
          title="Full Name" 
          key="fullName" 
          render={(text, record) => `${record.name} ${record.lastname}`} 
        />
        <Column title="Email" dataIndex="email" key="email" />
        <Column title="Téléphone" dataIndex="tele" key="tele" />
        <Column title="Cin" dataIndex="cin" key="cin" />
        <Column title="Date d'Intégration" dataIndex="datedintegration" key="datedintegration" render={(text) => text ? moment(text).format('YYYY-MM-DD') : ''} />
        <Column title="Salaire" dataIndex="salaire" key="salaire" />
        <Column
          title="Actions"
          key="actions"
          render={(text, record) => (
            <>
              <Button 
                icon={<EditOutlined />} 
                onClick={() => showModal(record)} 
                style={{ marginRight: 8 }} 
                type="primary" 
                ghost
              />
              <Button 
                icon={<DeleteOutlined />} 
                onClick={() => handleDeleteCourcier(record.id)} 
                danger
              />
            </>
          )}
        />
      </Table>

      <Modal
        title={editingCourcier ? 'Edit Coursier' : 'Add Coursier'}
        visible={isModalVisible}
        onCancel={() => setIsModalVisible(false)}
        footer={null}
        destroyOnClose
      >
        <Form 
          form={form} 
          onFinish={editingCourcier ? handleUpdateCourcier : handleAddCourcier} 
          layout="vertical"
        >
          <Form.Item 
            name="nom" 
            label="Nom" 
            rules={[{ required: true, message: 'Please input the nom!' }]}
          >
            <Input />
          </Form.Item>
          <Form.Item 
            name="prenom" 
            label="Prenom" 
            rules={[{ required: true, message: 'Please input the prenom!' }]}
          >
            <Input />
          </Form.Item>
          <Form.Item 
            name="tele" 
            label="Téléphone" 
            rules={[{ required: true, message: 'Please input the téléphone!' }]}
          >
            <Input />
          </Form.Item>
          <Form.Item 
            name="salaire" 
            label="Salaire" 
            rules={[{ required: true, message: 'Please input the salaire!' }]}
          >
            <Input type="number" />
          </Form.Item>
          <Form.Item
            name="role"
            initialValue="courcier"
            hidden
          >
            <Input type="hidden" />
          </Form.Item>
          <Form.Item>
            <Button 
              type="primary" 
              htmlType="submit"
            >
              {editingCourcier ? 'Update' : 'Add'}
            </Button>
            {editingCourcier && (
              <Button 
                type="default" 
                onClick={resetPassword} 
                style={{ marginLeft: 8 }}
                icon={<KeyOutlined />}
              >
                Reset Password
              </Button>
            )}
          </Form.Item>
        </Form>
      </Modal>
    </>
  );
};

export default CoursierList;
