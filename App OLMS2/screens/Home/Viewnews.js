import React, { Component } from 'react'
import { Container, Header, Content, Card, CardItem, Thumbnail, Text, Button, Icon, Left, Body } from 'native-base';
import {FlatList, StyleSheet, View} from 'react-native';
import Viewnews from './Viewnews';
import moment from 'moment'
export default class News extends React.Component {
  constructor(props){
    super(props);
  }


  render() {
    return (
        <Card style={{flex: 0}} >
            <CardItem>
                <Left>
                    <Body>
                    <Text>{this.props.name} ({this.props.code})</Text>
                    {/* {this.renderNewstime(item.news)} */}
                    </Body>
                </Left>
            </CardItem>
            <CardItem>
                <Body>
                    {/* {this.props} */}
                </Body>
            </CardItem>
        </Card>
    )
  }
}
