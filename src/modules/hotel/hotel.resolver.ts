import { Args, Mutation, Query, Resolver } from '@nestjs/graphql';

@Resolver('Hotel')
export class HotelResolver {
  private hotels = [
    {
      id: 1,
      name: 'Grand Palace Hotel',
      address: '123 Royal Street',
      phone: '0123456789',
    },
    {
      id: 2,
      name: 'Sunshine Inn',
      address: '456 Sunset Blvd',
      phone: '0987654321',
    },
  ];

  // Fetch a single hotel by ID
  @Query('getHotel')
  getHotelById(@Args('id') id: number) {
    return this.hotels.find((hotel) => hotel.id === id);
  }

  // Fetch all hotels
  @Query('getHotels')
  getAllHotels() {
    return this.hotels;
  }

  // Create a new hotel
  @Mutation('createHotel')
  createHotel(
    @Args('name') name: string,
    @Args('address') address: string,
    @Args('phone') phone: string,
  ) {
    if (!name  || !address ||  !phone) {
      throw new Error('All hotel fields are required');
    }

    const sortedHotels = this.hotels.sort((a, b) => a.id - b.id);
    const lastId = sortedHotels.length > 0 ? sortedHotels[sortedHotels.length - 1].id : 0;
    const newHotel = {
      id: lastId + 1,
      name,
      address,
      phone,
    };
    this.hotels.push(newHotel);
    return newHotel;
  }

  // Update an existing hotel
  @Mutation('updateHotel')
  updateHotel(
    @Args('id') id: number,
    @Args('name') name: string,
    @Args('address') address: string,
    @Args('phone') phone: string,
  ) {
    const hotelIndex = this.hotels.findIndex((hotel) => hotel.id === id);
    if (hotelIndex === -1) {
      throw new Error('Hotel not found');
    }
    const updatedHotel = {
      ...this.hotels[hotelIndex],
      name: name || this.hotels[hotelIndex].name,
      address: address || this.hotels[hotelIndex].address,
      phone: phone || this.hotels[hotelIndex].phone,
    };
    this.hotels[hotelIndex] = updatedHotel;
    return updatedHotel;
  }

  // Delete an existing hotel
  @Mutation('deleteHotel')
  deleteHotel(@Args('id') id: number) {
    const hotelIndex = this.hotels.findIndex((hotel) => hotel.id === id);
    if (hotelIndex === -1) {
      throw new Error('Hotel not found');
    }
    this.hotels.splice(hotelIndex, 1);
    return true;
  }
}