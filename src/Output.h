#ifndef Output_h
#define Output_h

#include "Utils.h"
#include <iostream>
#include <string>

/* Interface for data output component */
class Output
{
public:
  Output();
  virtual ~Output();

  /* Initialize output component and report any failures */
  virtual int init();

  /* Write data in output file */
  virtual int outputData(std::string &data);

  /* Get output and write it in output file */
  virtual int outputData(std::string &digest, std::string &name);
};

#endif
